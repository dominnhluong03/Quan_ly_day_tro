<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Contract;
<<<<<<< HEAD
=======
use App\Models\Payment;
>>>>>>> feb1f02 (first commit)

class TenantBillController extends Controller
{
    public function index()
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

<<<<<<< HEAD
        // ✅ Lấy danh sách room_id mà tenant này đang/đã có hợp đồng (ưu tiên active)
        $roomIds = Contract::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active','expired']) // tuỳ bạn, muốn xem cả expired thì để
=======
        $roomIds = Contract::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active','expired'])
>>>>>>> feb1f02 (first commit)
            ->pluck('room_id')
            ->unique()
            ->values()
            ->toArray();

<<<<<<< HEAD
        // ✅ Lấy invoices theo room_id của contract (không ràng tenant_id trực tiếp)
        $invoices = Invoice::with(['contract.room', 'contract.tenant.user'])
=======
        $invoices = Invoice::with(['contract.room'])
>>>>>>> feb1f02 (first commit)
            ->whereHas('contract', function ($q) use ($roomIds) {
                $q->whereIn('room_id', $roomIds);
            })
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderByDesc('id')
            ->get();

<<<<<<< HEAD
        return view('tenant.bills.index', compact('invoices'));
    }

=======
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


>>>>>>> feb1f02 (first commit)
    public function view(Invoice $invoice)
    {
        $this->authorizeInvoiceByRoom($invoice);

<<<<<<< HEAD
        if (!$invoice->invoice_file) abort(404, 'Chưa có file hóa đơn');

        $relative = str_replace('storage/', '', $invoice->invoice_file);
        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) abort(404, 'File hóa đơn không tồn tại');
=======
        if (!$invoice->invoice_file) {
            abort(404, 'Chưa có file hóa đơn');
        }

        $relative = str_replace('storage/', '', $invoice->invoice_file);

        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) {
            abort(404, 'File hóa đơn không tồn tại');
        }
>>>>>>> feb1f02 (first commit)

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

<<<<<<< HEAD
=======

>>>>>>> feb1f02 (first commit)
    public function download(Invoice $invoice)
    {
        $this->authorizeInvoiceByRoom($invoice);

<<<<<<< HEAD
        if (!$invoice->invoice_file) abort(404, 'Chưa có file hóa đơn');

        $relative = str_replace('storage/', '', $invoice->invoice_file);
        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) abort(404, 'File hóa đơn không tồn tại');
=======
        if (!$invoice->invoice_file) {
            abort(404, 'Chưa có file hóa đơn');
        }

        $relative = str_replace('storage/', '', $invoice->invoice_file);

        $path = Storage::disk('public')->path($relative);

        if (!file_exists($path)) {
            abort(404, 'File hóa đơn không tồn tại');
        }
>>>>>>> feb1f02 (first commit)

        return response()->download($path, basename($path), [
            'Content-Type' => 'application/pdf',
        ]);
    }

<<<<<<< HEAD
=======

>>>>>>> feb1f02 (first commit)
    private function authorizeInvoiceByRoom(Invoice $invoice): void
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

<<<<<<< HEAD
        $roomIdOfInvoice = (int)($invoice->contract?->room_id);

        // tenant phải có hợp đồng với phòng đó (active/expired)
=======
        $roomIdOfInvoice = (int) ($invoice->contract?->room_id);

>>>>>>> feb1f02 (first commit)
        $hasRoom = Contract::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active','expired'])
            ->where('room_id', $roomIdOfInvoice)
            ->exists();

        if (!$hasRoom) {
            abort(403, 'Bạn không có quyền xem hóa đơn của phòng này.');
        }
    }
}