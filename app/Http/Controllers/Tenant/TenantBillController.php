<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Contract;
use App\Models\Payment;

class TenantBillController extends Controller
{
    public function index()
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();
        // ✅ Lấy danh sách room_id mà tenant này đang/đã có hợp đồng (ưu tiên active)
        $roomIds = Contract::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active','expired']) // tuỳ bạn, muốn xem cả expired thì để
        $roomIds = Contract::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active','expired'])
            ->pluck('room_id')
            ->unique()
            ->values()
            ->toArray();


        // ✅ Lấy invoices theo room_id của contract (không ràng tenant_id trực tiếp)
        $invoices = Invoice::with(['contract.room', 'contract.tenant.user'])
        $invoices = Invoice::with(['contract.room']
            ->whereHas('contract', function ($q) use ($roomIds) {
                $q->whereIn('room_id', $roomIds);
            })
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->get();


        return view('tenant.bills.index', compact('invoices'));
    }

        // lấy payment mới nhất
        $latestPayments = Payment::whereIn('invoice_id', $invoices->pluck('id'))
            ->orderByDesc('id')
            ->get()
            ->groupBy('invoice_id')
            ->map(function ($items) {
                return $items->first();
            });

        $invoices->transform(function ($invoice) use ($latestPayments) {
            $invoice->latest_payment = $latestPayments[$invoice->id] ?? null;
            return $invoice;
        });

        return view('tenant.bills.index', compact('invoices'));
    }

    public function view(Invoice $invoice)
    {
        $this->authorizeInvoiceByRoom($invoice);

        if (!$invoice->invoice_file) abort(404, 'Chưa có file hóa đơn');

        $relative = str_replace('storage/', '', $invoice->invoice_file);
        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) abort(404, 'File hóa đơn không tồn tại');
        if (!$invoice->invoice_file) {
            abort(404, 'Chưa có file hóa đơn');
        }

        $relative = str_replace('storage/', '', $invoice->invoice_file);

        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) {
            abort(404, 'File hóa đơn không tồn tại');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }


    public function download(Invoice $invoice)
    {
        $this->authorizeInvoiceByRoom($invoice);
        if (!$invoice->invoice_file) abort(404, 'Chưa có file hóa đơn');

        $relative = str_replace('storage/', '', $invoice->invoice_file);
        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) abort(404, 'File hóa đơn không tồn tại');
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

    private function authorizeInvoiceByRoom(Invoice $invoice): void
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();
        $roomIdOfInvoice = (int)($invoice->contract?->room_id);

        // tenant phải có hợp đồng với phòng đó (active/expired)
        $roomIdOfInvoice = (int) ($invoice->contract?->room_id);

        $hasRoom = Contract::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active','expired'])
            ->where('room_id', $roomIdOfInvoice)
            ->exists();

        if (!$hasRoom) {
            abort(403, 'Bạn không có quyền xem hóa đơn của phòng này.');
        }
    }
}