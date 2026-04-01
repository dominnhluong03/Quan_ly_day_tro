<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Issue;

class IssueController extends Controller
{
    /**
     * GET /admin/issues?tab=pending|fixing|resolved
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        // whitelist tab
        if (!in_array($tab, ['pending', 'fixing', 'resolved'], true)) {
            $tab = 'pending';
        }

        $issues = Issue::with(['room', 'tenant.user', 'asset'])
            ->where('status', $tab)
            ->orderByDesc('id')
            ->get();

        return view('admin.issues.index', compact('issues', 'tab'));
    }

    /**
     * PATCH /admin/issues/{issue}/fixing
     * Chuyển trạng thái: pending -> fixing
     */
    public function markFixing(Issue $issue)
    {
        // Nếu đã resolved thì không cho quay lại fixing
        if ($issue->status === 'resolved') {
            return back()->withErrors(['error' => 'Sự cố đã hoàn thành rồi.']);
        }

        // Nếu đã fixing thì thôi (idempotent)
        if ($issue->status === 'fixing') {
            return back()->with('success', 'Sự cố đã ở trạng thái: Đang sửa.');
        }

        $issue->update([
            'status'      => 'fixing',
            'resolved_at' => null, // đảm bảo sạch dữ liệu
        ]);

        return back()->with('success', 'Đã chuyển sang trạng thái: Đang sửa.');
    }

    /**
     * PATCH /admin/issues/{issue}/resolved
     * Chuyển trạng thái: pending|fixing -> resolved + set resolved_at
     */
    public function markResolved(Issue $issue)
    {
        // Nếu đã resolved thì thôi (idempotent)
        if ($issue->status === 'resolved') {
            return back()->with('success', 'Sự cố đã ở trạng thái: Sửa xong.');
        }

        DB::beginTransaction();
        try {
            $issue->update([
                'status'      => 'resolved',
                'resolved_at' => now(),
            ]);

            // (Tuỳ chọn) nếu bạn muốn cập nhật tài sản về good khi sửa xong:
            // if ($issue->asset) {
            //     $issue->asset->update(['status' => 'good']);
            // }

            DB::commit();
            return back()->with('success', 'Đã xác nhận: Sửa xong.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi cập nhật: ' . $e->getMessage()]);
        }
    }

    /**
     * (Tuỳ chọn) PATCH /admin/issues/{issue}/pending
     * Nếu bạn muốn cho phép chuyển về pending (thường không cần).
     */
    public function markPending(Issue $issue)
    {
        if ($issue->status === 'resolved') {
            return back()->withErrors(['error' => 'Sự cố đã hoàn thành, không thể chuyển về chờ xử lý.']);
        }

        $issue->update([
            'status'      => 'pending',
            'resolved_at' => null,
        ]);

        return back()->with('success', 'Đã chuyển về trạng thái: Chờ xử lý.');
    }
}