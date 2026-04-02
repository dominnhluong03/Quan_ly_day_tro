<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Room;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Tự động cập nhật hợp đồng active nhưng đã quá hạn thành expired,
     * đồng thời đồng bộ trạng thái tenant/room.
     */
    private function refreshExpiredContracts(): void
    {
        $today = Carbon::today()->toDateString();

        $expiredContracts = Contract::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', $today)
            ->get();

        foreach ($expiredContracts as $contract) {
            $contract->update(['status' => 'expired']);

            $stillActiveTenant = Contract::where('tenant_id', $contract->tenant_id)
                ->where('status', 'active')
                ->exists();

            if (! $stillActiveTenant) {
                Tenant::where('id', $contract->tenant_id)->update(['status' => 'free']);
            }

            $room = Room::find($contract->room_id);

            if ($room && $room->status !== 'maintenance') {
                $stillActiveRoom = Contract::where('room_id', $contract->room_id)
                    ->where('status', 'active')
                    ->exists();

                if (! $stillActiveRoom) {
                    $room->update(['status' => 'empty']);
                }
            }
        }
    }

    public function index()
    {
        $this->refreshExpiredContracts();

        $contracts = Contract::with(['tenant.user', 'room'])
            ->orderByDesc('id')
            ->get();

        $tenants = Tenant::with('user')
            ->whereDoesntHave('contracts', function ($q) {
                $q->where('status', 'active');
            })
            ->orderByDesc('id')
            ->get();

        $rooms = Room::with([
                'contracts' => function ($q) {
                    $q->where('status', 'active')
                        ->with(['tenant.user'])
                        ->orderByDesc('id');
                },
            ])
            ->withCount([
                'contracts as active_count' => function ($q) {
                    $q->where('status', 'active');
                },
            ])
            ->get()
            ->filter(function ($room) {
                return $room->active_count < ($room->max_people ?? 1);
            })
            ->values();

        return view('admin.contracts.index', compact('contracts', 'rooms', 'tenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'deposit' => 'required|numeric|min:0',
            'electric_price' => 'required|numeric|min:0',
            'water_price' => 'required|numeric|min:0',
            'service_note' => 'nullable|string',
        ]);

        $room = Room::findOrFail($data['room_id']);

        $tenantIds = is_array($request->tenant_id)
            ? $request->tenant_id
            : [$request->tenant_id];

        $tenantIds = array_values(array_unique(array_filter($tenantIds)));

        $activeCount = Contract::where('room_id', $room->id)
            ->where('status', 'active')
            ->count();

        $maxPeople = (int) ($room->max_people ?? 1);

        if ($activeCount + count($tenantIds) > $maxPeople) {
            return back()->withInput()->withErrors([
                'room_id' => "Phòng {$room->room_code} tối đa {$maxPeople} người.",
            ]);
        }

        DB::beginTransaction();

        try {
            $created = [];

            foreach ($tenantIds as $tenantId) {
                $contract = Contract::create([
                    'room_id' => $room->id,
                    'tenant_id' => $tenantId,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'] ?? null,
                    'deposit' => $data['deposit'],
                    'status' => 'active',
                    'electric_price' => $data['electric_price'],
                    'water_price' => $data['water_price'],
                    'service_note' => $data['service_note'] ?? null,
                ]);

                if ($contract->end_date && Carbon::parse($contract->end_date)->lt(Carbon::today())) {
                    $contract->update(['status' => 'expired']);
                }

                if ($contract->status === 'active') {
                    $contract->tenant->update(['status' => 'renting']);

                    if ($room->status !== 'maintenance') {
                        $room->update(['status' => 'occupied']);
                    }
                }

                $contract->load(['room', 'tenant.user']);

                $pdf = Pdf::loadView('admin.contracts.pdf', [
                    'contract' => $contract,
                ]);

                $folder = public_path('contracts');

                if (! file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $fileName = 'contract_' . $contract->id . '.pdf';
                $pdf->save($folder . '/' . $fileName);

                $contract->update([
                    'contract_file' => 'contracts/' . $fileName,
                ]);

                $created[] = $contract->id;
            }

            $this->refreshExpiredContracts();

            DB::commit();

            return redirect()
                ->route('admin.contracts.index')
                ->with('success', 'Đã tạo hợp đồng: #' . implode(', #', $created));
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit(Contract $contract)
    {
        $this->refreshExpiredContracts();

        $contract->load(['tenant.user', 'room']);
        $rooms = Room::orderBy('room_code')->get();

        return view('admin.contracts.edit', compact('contract', 'rooms'));
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'deposit' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,cancelled',
            'electric_price' => 'required|numeric|min:0',
            'water_price' => 'required|numeric|min:0',
            'service_note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $oldRoomId = (int) $contract->room_id;
            $oldTenantId = (int) $contract->tenant_id;

            if (! empty($data['end_date']) && Carbon::parse($data['end_date'])->lt(Carbon::today())) {
                $data['status'] = 'expired';
            }

            $contract->update($data);

            if ($data['status'] === 'active') {
                $contract->tenant->update(['status' => 'renting']);
            } else {
                $stillActive = Contract::where('tenant_id', $contract->tenant_id)
                    ->where('status', 'active')
                    ->count();

                if ($stillActive == 0) {
                    $contract->tenant->update(['status' => 'free']);
                }
            }

            $newRoom = Room::findOrFail($contract->room_id);

            if ($data['status'] === 'active') {
                if ($newRoom->status !== 'maintenance') {
                    $newRoom->update(['status' => 'occupied']);
                }
            } else {
                $activeInNewRoom = Contract::where('room_id', $newRoom->id)
                    ->where('status', 'active')
                    ->count();

                if ($activeInNewRoom == 0 && $newRoom->status !== 'maintenance') {
                    $newRoom->update(['status' => 'empty']);
                }
            }

            if ($oldRoomId !== (int) $contract->room_id) {
                $oldRoom = Room::find($oldRoomId);

                if ($oldRoom && $oldRoom->status !== 'maintenance') {
                    $activeInOldRoom = Contract::where('room_id', $oldRoomId)
                        ->where('status', 'active')
                        ->count();

                    if ($activeInOldRoom == 0) {
                        $oldRoom->update(['status' => 'empty']);
                    }
                }
            }

            if ($oldTenantId !== (int) $contract->tenant_id) {
                $stillActiveOldTenant = Contract::where('tenant_id', $oldTenantId)
                    ->where('status', 'active')
                    ->count();

                if ($stillActiveOldTenant == 0) {
                    Tenant::where('id', $oldTenantId)->update(['status' => 'free']);
                }
            }

            $contract->load(['room', 'tenant.user']);

            $pdf = Pdf::loadView('admin.contracts.pdf', [
                'contract' => $contract,
            ]);

            $folder = public_path('contracts');

            if (! file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $fileName = 'contract_' . $contract->id . '.pdf';
            $pdf->save($folder . '/' . $fileName);

            $contract->update([
                'contract_file' => 'contracts/' . $fileName,
            ]);

            $this->refreshExpiredContracts();

            DB::commit();

            return redirect()
                ->route('admin.contracts.index')
                ->with('success', 'Đã cập nhật hợp đồng #' . $contract->id);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function view(Contract $contract)
    {
        $this->refreshExpiredContracts();

        if (! $contract->contract_file) {
            abort(404);
        }

        $path = public_path($contract->contract_file);

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function destroy(Contract $contract)
    {
        $this->refreshExpiredContracts();

        DB::beginTransaction();

        try {
            $tenantId = (int) $contract->tenant_id;
            $roomId = (int) $contract->room_id;

            $contract->delete();

            $stillActiveTenant = Contract::where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->count();

            if ($stillActiveTenant == 0) {
                Tenant::where('id', $tenantId)->update(['status' => 'free']);
            }

            $room = Room::find($roomId);

            if ($room && $room->status !== 'maintenance') {
                $stillActiveRoom = Contract::where('room_id', $roomId)
                    ->where('status', 'active')
                    ->count();

                if ($stillActiveRoom == 0) {
                    $room->update(['status' => 'empty']);
                }
            }

            DB::commit();

            return back()->with('success', 'Đã xóa hợp đồng');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }
}