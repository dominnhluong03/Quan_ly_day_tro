<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Payment;
use App\Models\Invoice;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // tab: pending | approved (mặc định pending)
        $tab = $request->get('tab', 'pending');
        if (!in_array($tab, ['pending', 'approved'])) {
            $tab = 'pending';
        }

        $payments = Payment::with(['invoice.contract.room','invoice.contract.tenant.user'])
            ->when($tab === 'pending', fn($q) => $q->where('status', 'pending'))
            ->when($tab === 'approved', fn($q) => $q->where('status', 'approved'))
            ->orderByDesc('id')
            ->get();

        return view('admin.payments.index', compact('payments', 'tab'));
    }

    public function approve(Payment $payment)
    {
        $payment->load(['invoice.contract']);

        $invoice = $payment->invoice;
        if (!$invoice || !$invoice->contract) abort(404);

        $roomId = (int) $invoice->contract->room_id;
        $month  = (int) $invoice->month;
        $year   = (int) $invoice->year;

        DB::beginTransaction();
        try {
            // 1) lấy tất cả invoice cùng phòng + cùng kỳ
            $invoiceIds = Invoice::whereHas('contract', fn($q) => $q->where('room_id', $roomId))
                ->where('month', $month)
                ->where('year', $year)
                ->pluck('id');

            if ($invoiceIds->isEmpty()) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Không tìm thấy hóa đơn cùng phòng/kỳ để duyệt.']);
            }

            // 2) set PAID cho toàn bộ invoices kỳ đó
            Invoice::whereIn('id', $invoiceIds)->update([
                'status' => 'paid',
            ]);

            // 3) set APPROVED cho toàn bộ payments thuộc các invoices đó
            // (chỉ update các payment đang pending để tránh overwrite lịch sử)
            Payment::whereIn('invoice_id', $invoiceIds)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);

            DB::commit();
            return back()->with('success', "Đã xác nhận: Cả phòng kỳ {$month}/{$year} đã được cập nhật PAID.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi duyệt: ' . $e->getMessage()]);
        }
    }

    public function viewFile(Payment $payment)
    {
        if (!$payment->image_path) abort(404, 'Chưa có file');

        $path = $payment->image_path; // ví dụ: payments/xxx.png

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File không tồn tại');
        }

        return response()->file(Storage::disk('public')->path($path), [
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    public function downloadFile(Payment $payment)
    {
        if (!$payment->image_path) abort(404, 'Chưa có file');

        $path = $payment->image_path;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File không tồn tại');
        }

        return Storage::disk('public')->download($path, basename($path));
    }

    public function reject(Request $request, Payment $payment)
{
    // chỉ cho reject khi đang pending
    if ($payment->status !== 'pending') {
        return back()->withErrors(['error' => 'Chỉ có thể từ chối khi payment đang ở trạng thái chờ duyệt.']);
    }

    $data = $request->validate([
        'note' => 'nullable|string|max:255',
    ]);

    $payment->update([
        'status' => 'rejected',
        'note' => $data['note'] ?? $payment->note,
        'approved_at' => null, // giữ sạch dữ liệu
    ]);

    return back()->with('success', 'Đã từ chối chứng từ thanh toán.');
}
}