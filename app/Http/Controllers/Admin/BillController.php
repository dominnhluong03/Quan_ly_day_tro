<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Models\Invoice;
use App\Models\InvoiceService;
use App\Models\MeterReading;
use App\Models\Contract;
use App\Models\Room;

use Barryvdh\DomPDF\Facade\Pdf;

class BillController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with([
            'contract.room',
            'contract.tenant.user',
            'services',
            'meter',
        ])
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->get();

        $rooms = Room::with(['contracts' => function ($q) {
            $q->where('status', 'active')->orderByDesc('id');
        }])
            ->orderBy('room_code')
            ->get()
            ->map(function ($room) {
                $active = $room->contracts->first();

                $room->active_contract_id    = $active?->id;
                $room->active_room_price     = (int) ($room->price ?? 0);
                $room->active_electric_price = (int) ($active?->electric_price ?? 0);
                $room->active_water_price    = (int) ($active?->water_price ?? 0);

                $latestInvoice = Invoice::whereHas('contract', function ($q) use ($room) {
                    $q->where('room_id', $room->id);
                })
                    ->with('meter')
                    ->orderByDesc('year')
                    ->orderByDesc('month')
                    ->orderByDesc('id')
                    ->first();

                $room->last_electric_old = (int) ($latestInvoice?->meter?->electric_old ?? 0);
                $room->last_electric_new = (int) ($latestInvoice?->meter?->electric_new ?? 0);
                $room->last_water_old    = (int) ($latestInvoice?->meter?->water_old ?? 0);
                $room->last_water_new    = (int) ($latestInvoice?->meter?->water_new ?? 0);

                return $room;
            })
            ->filter(fn($r) => $r->active_contract_id)
            ->values();

        return view('admin.bills.index', compact('invoices', 'rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'month'            => 'required|integer|min:1|max:12',
            'year'             => 'required|integer|min:2000|max:2100',
            'electric_old'     => 'nullable|integer|min:0',
            'electric_new'     => 'nullable|integer|min:0',
            'water_old'        => 'nullable|integer|min:0',
            'water_new'        => 'nullable|integer|min:0',
            'service_name'     => 'nullable|array',
            'service_name.*'   => 'nullable|string|max:255',
            'service_price'    => 'nullable|array',
            'service_price.*'  => 'nullable|numeric|min:0',
        ]);

        $room = Room::findOrFail($data['room_id']);

        $contract = Contract::where('room_id', $room->id)
            ->where('status', 'active')
            ->orderByDesc('id')
            ->first();

        if (!$contract) {
            return back()->withErrors([
                'room_id' => 'Phòng chưa có hợp đồng active.',
            ])->withInput();
        }

        $invoiceDate   = Carbon::createFromDate($data['year'], $data['month'], 1);
        $contractStart = Carbon::parse($contract->start_date)->startOfMonth();

        if ($contract->end_date) {
            $contractEnd = Carbon::parse($contract->end_date)->endOfMonth();

            if ($invoiceDate->lt($contractStart) || $invoiceDate->gt($contractEnd)) {
                return back()->withErrors([
                    'month' => 'Tháng không nằm trong thời gian hợp đồng.',
                ])->withInput();
            }
        } elseif ($invoiceDate->lt($contractStart)) {
            return back()->withErrors([
                'month' => 'Không thể tạo trước ngày bắt đầu hợp đồng.',
            ])->withInput();
        }

        $exists = Invoice::where('month', $data['month'])
            ->where('year', $data['year'])
            ->whereHas('contract', function ($q) use ($room) {
                $q->where('room_id', $room->id);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'month' => 'Đã có hóa đơn tháng này rồi.',
            ])->withInput();
        }

        $previousInvoice = Invoice::whereHas('contract', function ($q) use ($room) {
            $q->where('room_id', $room->id);
        })
            ->with('meter')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->first();

        $electricOld = $request->filled('electric_old')
            ? (int) $request->electric_old
            : (int) ($previousInvoice?->meter?->electric_new ?? 0);

        $waterOld = $request->filled('water_old')
            ? (int) $request->water_old
            : (int) ($previousInvoice?->meter?->water_new ?? 0);

        $electricNew = (int) ($request->electric_new ?? 0);
        $waterNew    = (int) ($request->water_new ?? 0);

        if ($electricNew < $electricOld) {
            return back()->withErrors([
                'electric_new' => 'Chỉ số điện mới không được nhỏ hơn chỉ số điện cũ.',
            ])->withInput();
        }

        if ($waterNew < $waterOld) {
            return back()->withErrors([
                'water_new' => 'Chỉ số nước mới không được nhỏ hơn chỉ số nước cũ.',
            ])->withInput();
        }

        $electricUsage = $electricNew - $electricOld;
        $waterUsage    = $waterNew - $waterOld;

        $electricCost = $electricUsage * (int) $contract->electric_price;
        $waterCost    = $waterUsage * (int) $contract->water_price;

        $serviceTotal = 0;
        $services = [];
        $names  = $request->service_name ?? [];
        $prices = $request->service_price ?? [];

        for ($i = 0; $i < max(count($names), count($prices)); $i++) {
            $name  = trim((string) ($names[$i] ?? ''));
            $price = (int) ($prices[$i] ?? 0);

            if ($name !== '' && $price > 0) {
                $services[] = [
                    'service_name' => $name,
                    'price'        => $price,
                ];
                $serviceTotal += $price;
            }
        }

        $roomPrice = (int) $room->price;
        $total = $roomPrice + $electricCost + $waterCost + $serviceTotal;

        DB::beginTransaction();

        try {
            $meter = MeterReading::create([
                'room_id'       => $room->id,
                'month'         => $data['month'],
                'year'          => $data['year'],
                'electric_old'  => $electricOld,
                'electric_new'  => $electricNew,
                'water_old'     => $waterOld,
                'water_new'     => $waterNew,
                'created_at'    => now(),
            ]);

            $invoice = Invoice::create([
                'contract_id'    => $contract->id,
                'meter_id'       => $meter->id,
                'month'          => $data['month'],
                'year'           => $data['year'],
                'room_price'     => $roomPrice,
                'electric_cost'  => $electricCost,
                'water_cost'     => $waterCost,
                'service_total'  => $serviceTotal,
                'total'          => $total,
                'status'         => 'unpaid',
                'qr_image'       => 'storage/qr/owner_qr.jpg',
                'created_at'     => now(),
            ]);

            foreach ($services as $service) {
                InvoiceService::create([
                    'invoice_id'   => $invoice->id,
                    'service_name' => $service['service_name'],
                    'price'        => $service['price'],
                ]);
            }

            $pdfPath = $this->generatePdf($invoice->id);
            $invoice->update(['invoice_file' => $pdfPath]);

            DB::commit();

            return redirect()->route('admin.bills.index')
                ->with('success', 'Tạo hóa đơn thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage(),
            ])->withInput();
        }
    }

    private function generatePdf(int $invoiceId): string
    {
        $invoice = Invoice::with([
            'contract.room',
            'contract.tenant.user',
            'services',
            'meter',
        ])->findOrFail($invoiceId);

        $pdf = Pdf::loadView('admin.bills.pdf', compact('invoice'))->setPaper('a4');

        $filename = 'invoices/invoice_' . $invoice->id . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return 'storage/' . $filename;
    }

    public function view(Invoice $invoice)
    {
        if (!$invoice->invoice_file) {
            abort(404, 'Chưa có file hóa đơn');
        }

        $relative = str_replace('storage/', '', $invoice->invoice_file);
        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) {
            abort(404, 'File hóa đơn không tồn tại');
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function download(Invoice $invoice)
    {
        if (!$invoice->invoice_file) {
            abort(404, 'Chưa có file hóa đơn');
        }

        $relative = str_replace('storage/', '', $invoice->invoice_file);
        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) {
            abort(404, 'File hóa đơn không tồn tại');
        }

        return response()->download($path, basename($path), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();

        try {
            if ($invoice->invoice_file) {
                $relative = str_replace('storage/', '', $invoice->invoice_file);

                if (Storage::disk('public')->exists($relative)) {
                    Storage::disk('public')->delete($relative);
                }
            }

            InvoiceService::where('invoice_id', $invoice->id)->delete();

            if ($invoice->meter_id) {
                MeterReading::where('id', $invoice->meter_id)->delete();
            }

            $invoice->delete();

            DB::commit();

            return redirect()->route('admin.bills.index')
                ->with('success', 'Đã xoá hóa đơn thành công.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Lỗi khi xoá: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load([
            'contract.room',
            'contract.tenant.user',
            'services',
            'meter',
        ]);

        return view('admin.bills.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->load(['contract.room', 'services', 'meter']);

        $data = $request->validate([
            'month'         => 'required|integer|min:1|max:12',
            'year'          => 'required|integer|min:2000|max:2100',
            'electric_old'  => 'nullable|integer|min:0',
            'electric_new'  => 'nullable|integer|min:0',
            'water_old'     => 'nullable|integer|min:0',
            'water_new'     => 'nullable|integer|min:0',
            'service_name'  => 'array',
            'service_price' => 'array',
            'status'        => 'required|in:unpaid,paid',
        ]);

        $contract = $invoice->contract;
        $room = $contract->room;

        $exists = Invoice::where('id', '!=', $invoice->id)
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->whereHas('contract', function ($q) use ($room) {
                $q->where('room_id', $room->id);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'month' => 'Đã có hóa đơn tháng này rồi.',
            ])->withInput();
        }

        $electricOld = (int) ($request->electric_old ?? 0);
        $electricNew = (int) ($request->electric_new ?? 0);
        $waterOld    = (int) ($request->water_old ?? 0);
        $waterNew    = (int) ($request->water_new ?? 0);

        if ($electricNew < $electricOld) {
            return back()->withErrors([
                'electric_new' => 'Chỉ số điện mới không được nhỏ hơn chỉ số điện cũ.',
            ])->withInput();
        }

        if ($waterNew < $waterOld) {
            return back()->withErrors([
                'water_new' => 'Chỉ số nước mới không được nhỏ hơn chỉ số nước cũ.',
            ])->withInput();
        }

        $electricUsage = $electricNew - $electricOld;
        $waterUsage    = $waterNew - $waterOld;

        $electricCost = $electricUsage * (int) ($contract->electric_price ?? 0);
        $waterCost    = $waterUsage * (int) ($contract->water_price ?? 0);

        $serviceTotal = 0;
        $services = [];
        $names  = $request->service_name ?? [];
        $prices = $request->service_price ?? [];

        for ($i = 0; $i < max(count($names), count($prices)); $i++) {
            $name = trim((string) ($names[$i] ?? ''));
            $price = (int) ($prices[$i] ?? 0);

            if ($name !== '' && $price > 0) {
                $serviceTotal += $price;
                $services[] = [
                    'service_name' => $name,
                    'price'        => $price,
                ];
            }
        }

        $roomPrice = (int) ($room->price ?? 0);
        $total = $roomPrice + $electricCost + $waterCost + $serviceTotal;

        DB::beginTransaction();

        try {
            if ($invoice->meter) {
                $invoice->meter->update([
                    'month'         => $data['month'],
                    'year'          => $data['year'],
                    'electric_old'  => $electricOld,
                    'electric_new'  => $electricNew,
                    'water_old'     => $waterOld,
                    'water_new'     => $waterNew,
                ]);
            }

            $invoice->update([
                'month'         => $data['month'],
                'year'          => $data['year'],
                'room_price'    => $roomPrice,
                'electric_cost' => $electricCost,
                'water_cost'    => $waterCost,
                'service_total' => $serviceTotal,
                'total'         => $total,
                'status'        => $data['status'],
            ]);

            InvoiceService::where('invoice_id', $invoice->id)->delete();

            foreach ($services as $service) {
                InvoiceService::create([
                    'invoice_id'   => $invoice->id,
                    'service_name' => $service['service_name'],
                    'price'        => $service['price'],
                ]);
            }

            $pdfPath = $this->generatePdf($invoice->id);
            $invoice->update(['invoice_file' => $pdfPath]);

            DB::commit();

            return redirect()->route('admin.bills.index')
                ->with('success', 'Đã cập nhật hóa đơn & xuất PDF lại thành công!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Lỗi cập nhật: ' . $e->getMessage(),
            ])->withInput();
        }
    }
}