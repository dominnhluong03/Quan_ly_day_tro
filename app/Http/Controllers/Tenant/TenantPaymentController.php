<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Contract;

class TenantPaymentController extends Controller
{
    /**
     * Tenant upload chứng từ thanh toán cho invoice
     * - chỉ cần 1 người trong phòng upload
     * - nếu đã có pending/approved => không cho upload nữa (hoặc bạn có thể cho update)
     * Tenant upload chứng từ thanh toán
     */
    public function store(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoiceRoom($invoice);

        // nếu đã paid thì không cần upload nữa
        if ($invoice->status === 'paid') {
            return back()->withErrors(['error' => 'Hóa đơn này đã được thanh toán.']);
        }

        // ✅ nếu đã có payment pending/approved thì chặn upload lần nữa (đúng ý bạn: 1 người up là đủ)
        $existing = Payment::where('invoice_id', $invoice->id)
            ->whereIn('status', ['pending', 'approved'])
        // nếu invoice đã paid thì không cần gửi nữa
        if ($invoice->status === 'paid') {
            return back()->withErrors([
                'error' => 'Hóa đơn này đã được thanh toán.'
            ]);
        }

        // kiểm tra nếu đã có payment pending/approved
        $existing = Payment::where('invoice_id', $invoice->id)
            ->whereIn('status', ['pending','approved']
            ->latest('id')
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'Hóa đơn này đã có chứng từ thanh toán (đang chờ duyệt hoặc đã duyệt).']);
        }

        // ✅ validate đúng field "image"
            return back()->withErrors([
                'error' => 'Hóa đơn này đã có chứng từ thanh toán (đang chờ duyệt hoặc đã duyệt).'
            ]);
        }
        $data = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'note'  => 'nullable|string|max:255',
        ]);

        DB::beginTransaction()
        try {
            // lưu ảnh vào storage/app/public/payments
            $path = $request->file('image')->store('payments', 'public');

            Payment::create([
                'invoice_id' => $invoice->id,
                'image_path' => $path, // lưu dạng "payments/xxx.jpg"
                'note'       => $data['note'] ?? null,
                'status'     => 'pending',
                'approved_at'=> null,
            ]);

            DB::commit();
            return back()->with('success', 'Đã gửi chứng từ. Vui lòng chờ chủ trọ xác nhận.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi upload: '.$e->getMessage()]);
        }
    }

    /**
     * ✅ FIX lỗi authorizeInvoiceRoom() không tồn tại
     * Cho phép tenant upload nếu invoice thuộc phòng của tenant (qua contract.room_id)

        try {

            $path = $request->file('image')->store('payments', 'public');

            Payment::create([
                'invoice_id'  => $invoice->id,
                'image_path'  => $path,
                'note'        => $data['note'] ?? null,
                'status'      => 'pending',
                'approved_at' => null,
            ]);

            DB::commit();

            return back()->with('success', 'Đã gửi chứng từ thanh toán. Vui lòng chờ chủ trọ xác nhận.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withErrors([
                'error' => 'Lỗi upload: '.$e->getMessage()
            ]);
        }
    }


    /**
     * Kiểm tra tenant có quyền upload cho invoice không
     */
    private function authorizeInvoiceRoom(Invoice $invoice): void
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

        $invoice->loadMissing(['contract']);

        if (!$invoice->contract) {
            abort(404, 'Hóa đơn không có hợp đồng liên kết.');
        }
        // ✅ invoice thuộc về tenant nào cũng được miễn là cùng phòng (room_id)
        // Vì bạn muốn 2 người cùng phòng: 1 người up là đủ.
        // => check tenant có contract active cùng room_id với invoice.contract.room_id
        $roomId = (int) $invoice->contract->room_id;

        $hasAccess = \App\Models\Contract::where('tenant_id', $tenant->id)
        $roomId = (int) $invoice->contract->room_id;

        $hasAccess = Contract::where('tenant_id', $tenant->id)
            ->where('room_id', $roomId)
            ->where('status', 'active')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Bạn không có quyền upload cho hóa đơn này.');
        }
    }
}