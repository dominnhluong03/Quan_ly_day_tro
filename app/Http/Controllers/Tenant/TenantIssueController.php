<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Tenant;
use App\Models\Contract;
use App\Models\RoomAsset;
use App\Models\Issue;
use App\Models\User;

class TenantIssueController extends Controller
{
    private function currentTenantOrFail(): Tenant
    {
        return Tenant::where('user_id', auth()->id())->firstOrFail();
    }

    private function activeRoomIdForTenant(int $tenantId): ?int
    {
        return Contract::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderByDesc('id')
            ->value('room_id');
    }

    public function index()
    {
        $tenant = \App\Models\Tenant::where('user_id', auth()->id())->firstOrFail();

        $contract = \App\Models\Contract::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        $roomId = $contract?->room_id; // ✅ luôn có biến

        if (!$roomId) {
            return view('tenant.issues.index', [
                'roomId'     => null,
                'roomAssets' => collect(),
                'issues'     => collect(),
            ]);
        }

        $roomAssets = \App\Models\RoomAsset::where('room_id', $roomId)->get();

        $issues = \App\Models\Issue::with('roomAsset')
            ->where('room_id', $roomId)
            ->orderByDesc('id')
            ->get();

        return view('tenant.issues.index', compact('roomId','roomAssets','issues'));
    }

    public function store(Request $request)
    {
        $tenant = $this->currentTenantOrFail();
        $roomId = $this->activeRoomIdForTenant($tenant->id);

        if (!$roomId) abort(403, 'Bạn chưa có hợp đồng thuê phòng.');

        $data = $request->validate([
            'room_asset_id' => 'required|exists:room_assets,id',
            'title'         => 'nullable|string|max:255',
            'content'       => 'nullable|string|max:2000',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // đảm bảo asset thuộc phòng của tenant
        $asset = RoomAsset::where('id', $data['room_asset_id'])
            ->where('room_id', $roomId)
            ->first();

        if (!$asset) abort(403, 'Đồ dùng này không thuộc phòng của bạn.');

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('issues', 'public'); // issues/xxx.png
            }

            Issue::create([
                'room_id'       => $roomId,
                'tenant_id'     => $tenant->id,
                'room_asset_id' => $asset->id,
                'title'         => $data['title'] ?? ('Báo hỏng: '.$asset->name),
                'content'       => $data['content'] ?? null,
                'status'        => 'pending',
                'image_path'    => $imagePath,
            ]);

            // (tuỳ chọn) bắn thông báo cho admin bằng bảng notifications của bạn
            $adminIds = User::where('role','admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                DB::table('notifications')->insert([
                    'user_id'    => $adminId,
                    'title'      => 'Báo sự cố mới',
                    'content'    => "Phòng #{$roomId} báo hỏng: {$asset->name}",
                    'is_read'    => 0,
                    'created_at' => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Đã gửi báo sự cố cho chủ trọ.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Lỗi gửi sự cố: '.$e->getMessage()]);
        }
    }
}