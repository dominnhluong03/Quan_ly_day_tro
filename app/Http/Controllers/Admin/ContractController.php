<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
<<<<<<< HEAD
=======
use Carbon\Carbon;
>>>>>>> feb1f02 (first commit)

use App\Models\Contract;
use App\Models\Room;
use App\Models\Tenant;

class ContractController extends Controller
{
<<<<<<< HEAD
    public function index()
    {
        $contracts = Contract::with(['tenant.user','room'])
            ->orderByDesc('id')
            ->get();

        /*
        =====================================================
        1️⃣ LỌC TENANT
        Chỉ lấy tenant CHƯA có hợp đồng active
        =====================================================
        */
        $tenants = Tenant::with('user')
            ->whereDoesntHave('contracts', function($q){
                $q->where('status','active');
=======
    /**
     * Tự động cập nhật hợp đồng hết hạn
     */
    private function refreshExpiredContracts()
    {
        $today = Carbon::today()->toDateString();

        $expiredContracts = Contract::where('status', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', $today)
            ->get();

        foreach ($expiredContracts as $contract) {
            $contract->update([
                'status' => 'expired',
            ]);

            // Nếu tenant không còn hợp đồng active nào => free
            $stillActiveTenant = Contract::where('tenant_id', $contract->tenant_id)
                ->where('status', 'active')
                ->exists();

            if (! $stillActiveTenant) {
                Tenant::where('id', $contract->tenant_id)->update([
                    'status' => 'free',
                ]);
            }

            // Nếu room không còn hợp đồng active nào => empty (trừ maintenance)
            $room = Room::find($contract->room_id);

            if ($room && $room->status !== 'maintenance') {
                $stillActiveRoom = Contract::where('room_id', $contract->room_id)
                    ->where('status', 'active')
                    ->exists();

                if (! $stillActiveRoom) {
                    $room->update([
                        'status' => 'empty',
                    ]);
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

        // Tenant chưa có hợp đồng active
        $tenants = Tenant::with('user')
            ->whereDoesntHave('contracts', function ($q) {
                $q->where('status', 'active');
>>>>>>> feb1f02 (first commit)
            })
            ->orderByDesc('id')
            ->get();

<<<<<<< HEAD
        /*
        =====================================================
        2️⃣ LỌC ROOM
        Chỉ lấy phòng còn chỗ trống
        =====================================================
        */
        $rooms = Room::withCount([
                'contracts as active_count' => function($q){
                    $q->where('status','active');
                }
            ])
            ->get()
            ->filter(function($room){
                return $room->active_count < ($room->max_people ?? 1);
            })
            ->values(); // reset key

        return view('admin.contracts.index', compact('contracts','rooms','tenants'));
=======
        // Room còn chỗ + load hợp đồng active hiện tại để preview trong modal
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
>>>>>>> feb1f02 (first commit)
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id'      => 'required',
            'room_id'        => 'required|exists:rooms,id',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'deposit'        => 'required|numeric|min:0',
            'electric_price' => 'required|numeric|min:0',
            'water_price'    => 'required|numeric|min:0',
            'service_note'   => 'nullable|string',
        ]);

        $room = Room::findOrFail($data['room_id']);

<<<<<<< HEAD
        $tenantIds = is_array($request->tenant_id) ? $request->tenant_id : [$request->tenant_id];
        $tenantIds = array_values(array_unique(array_filter($tenantIds)));

        $activeCount = Contract::where('room_id',$room->id)->where('status','active')->count();
        $maxPeople = (int)($room->max_people ?? 1);

        if ($activeCount + count($tenantIds) > $maxPeople) {
            return back()->withInput()->withErrors([
                'room_id' => "Phòng {$room->room_code} tối đa {$maxPeople} người."
=======
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
>>>>>>> feb1f02 (first commit)
            ]);
        }

        DB::beginTransaction();
<<<<<<< HEAD
=======

>>>>>>> feb1f02 (first commit)
        try {
            $created = [];

            foreach ($tenantIds as $tenantId) {
<<<<<<< HEAD

=======
>>>>>>> feb1f02 (first commit)
                $contract = Contract::create([
                    'room_id'        => $room->id,
                    'tenant_id'      => $tenantId,
                    'start_date'     => $data['start_date'],
                    'end_date'       => $data['end_date'] ?? null,
                    'deposit'        => $data['deposit'],
                    'status'         => 'active',
                    'electric_price' => $data['electric_price'],
                    'water_price'    => $data['water_price'],
                    'service_note'   => $data['service_note'] ?? null,
                ]);

<<<<<<< HEAD
                // ✅ tenant -> renting
                $contract->tenant->update(['status' => 'renting']);

                // ✅ room -> occupied (trừ khi maintenance)
                if ($room->status !== 'maintenance') {
                    $room->update(['status' => 'occupied']);
                }

                // PDF
                $contract->load(['room','tenant.user']);
                $pdf = Pdf::loadView('admin.contracts.pdf', ['contract' => $contract]);

                $folder = public_path('contracts');
                if (!file_exists($folder)) mkdir($folder,0777,true);

                $fileName = 'contract_'.$contract->id.'.pdf';
                $pdf->save($folder.'/'.$fileName);

                $contract->update(['contract_file' => 'contracts/'.$fileName]);
=======
                // Nếu tạo hợp đồng mà end_date đã nhỏ hơn hôm nay => expired
                if ($contract->end_date && Carbon::parse($contract->end_date)->lt(Carbon::today())) {
                    $contract->update([
                        'status' => 'expired',
                    ]);
                }

                // tenant -> renting nếu contract active
                if ($contract->status === 'active') {
                    $contract->tenant->update([
                        'status' => 'renting',
                    ]);
                }

                // room -> occupied nếu contract active
                if ($contract->status === 'active' && $room->status !== 'maintenance') {
                    $room->update([
                        'status' => 'occupied',
                    ]);
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
>>>>>>> feb1f02 (first commit)

                $created[] = $contract->id;
            }

<<<<<<< HEAD
            DB::commit();

            return redirect()->route('admin.contracts.index')
                ->with('success','Đã tạo hợp đồng: #'.implode(', #',$created));

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()]);
=======
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
>>>>>>> feb1f02 (first commit)
        }
    }

    public function edit(Contract $contract)
    {
<<<<<<< HEAD
        $contract->load(['tenant.user','room']);
        $rooms = Room::orderBy('room_code')->get();
        return view('admin.contracts.edit', compact('contract','rooms'));
=======
        $this->refreshExpiredContracts();

        $contract->load(['tenant.user', 'room']);
        $rooms = Room::orderBy('room_code')->get();

        return view('admin.contracts.edit', compact('contract', 'rooms'));
>>>>>>> feb1f02 (first commit)
    }

    public function update(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'tenant_id'      => 'required|exists:tenants,id',
            'room_id'        => 'required|exists:rooms,id',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'deposit'        => 'required|numeric|min:0',
            'status'         => 'required|in:active,expired,cancelled',
            'electric_price' => 'required|numeric|min:0',
            'water_price'    => 'required|numeric|min:0',
            'service_note'   => 'nullable|string',
        ]);

        DB::beginTransaction();
<<<<<<< HEAD
        try {
            $oldRoomId = (int)$contract->room_id;
            $oldTenantId = (int)$contract->tenant_id;

            $contract->update($data);

            // ✅ tenant status
            if ($data['status'] === 'active') {
                $contract->tenant->update(['status'=>'renting']);
            } else {
                $stillActive = Contract::where('tenant_id',$contract->tenant_id)
                    ->where('status','active')
                    ->count();
                if ($stillActive == 0) {
                    $contract->tenant->update(['status'=>'free']);
                }
            }

            // ✅ room status cho phòng hiện tại
=======

        try {
            $oldRoomId = (int) $contract->room_id;
            $oldTenantId = (int) $contract->tenant_id;

            // Nếu end_date đã qua hôm nay thì ép expired
            if (! empty($data['end_date']) && Carbon::parse($data['end_date'])->lt(Carbon::today())) {
                $data['status'] = 'expired';
            }

            $contract->update($data);

            // tenant status
            if ($data['status'] === 'active') {
                $contract->tenant->update([
                    'status' => 'renting',
                ]);
            } else {
                $stillActive = Contract::where('tenant_id', $contract->tenant_id)
                    ->where('status', 'active')
                    ->count();

                if ($stillActive == 0) {
                    $contract->tenant->update([
                        'status' => 'free',
                    ]);
                }
            }

            // room status cho phòng hiện tại
>>>>>>> feb1f02 (first commit)
            $newRoom = Room::findOrFail($contract->room_id);

            if ($data['status'] === 'active') {
                if ($newRoom->status !== 'maintenance') {
<<<<<<< HEAD
                    $newRoom->update(['status'=>'occupied']);
                }
            } else {
                $activeInNewRoom = Contract::where('room_id',$newRoom->id)->where('status','active')->count();
                if ($activeInNewRoom == 0 && $newRoom->status !== 'maintenance') {
                    $newRoom->update(['status'=>'empty']);
                }
            }

            // ✅ nếu đổi phòng, kiểm tra phòng cũ có còn active không để trả về empty
            if ($oldRoomId !== (int)$contract->room_id) {
                $oldRoom = Room::find($oldRoomId);
                if ($oldRoom && $oldRoom->status !== 'maintenance') {
                    $activeInOldRoom = Contract::where('room_id',$oldRoomId)->where('status','active')->count();
                    if ($activeInOldRoom == 0) {
                        $oldRoom->update(['status'=>'empty']);
=======
                    $newRoom->update([
                        'status' => 'occupied',
                    ]);
                }
            } else {
                $activeInNewRoom = Contract::where('room_id', $newRoom->id)
                    ->where('status', 'active')
                    ->count();

                if ($activeInNewRoom == 0 && $newRoom->status !== 'maintenance') {
                    $newRoom->update([
                        'status' => 'empty',
                    ]);
                }
            }

            // Nếu đổi phòng
            if ($oldRoomId !== (int) $contract->room_id) {
                $oldRoom = Room::find($oldRoomId);

                if ($oldRoom && $oldRoom->status !== 'maintenance') {
                    $activeInOldRoom = Contract::where('room_id', $oldRoomId)
                        ->where('status', 'active')
                        ->count();

                    if ($activeInOldRoom == 0) {
                        $oldRoom->update([
                            'status' => 'empty',
                        ]);
>>>>>>> feb1f02 (first commit)
                    }
                }
            }

<<<<<<< HEAD
            // ✅ nếu đổi tenant, tenant cũ có còn active không -> free
            if ($oldTenantId !== (int)$contract->tenant_id) {
                $stillActiveOldTenant = Contract::where('tenant_id',$oldTenantId)->where('status','active')->count();
                if ($stillActiveOldTenant == 0) {
                    Tenant::where('id',$oldTenantId)->update(['status'=>'free']);
                }
            }

            // regen PDF
            $contract->load(['room','tenant.user']);
            $pdf = Pdf::loadView('admin.contracts.pdf', ['contract'=>$contract]);

            $folder = public_path('contracts');
            if (!file_exists($folder)) mkdir($folder,0777,true);

            $fileName = 'contract_'.$contract->id.'.pdf';
            $pdf->save($folder.'/'.$fileName);

            $contract->update(['contract_file'=>'contracts/'.$fileName]);

            DB::commit();

            return redirect()->route('admin.contracts.index')
                ->with('success','Đã cập nhật hợp đồng #'.$contract->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()]);
=======
            // Nếu đổi tenant
            if ($oldTenantId !== (int) $contract->tenant_id) {
                $stillActiveOldTenant = Contract::where('tenant_id', $oldTenantId)
                    ->where('status', 'active')
                    ->count();

                if ($stillActiveOldTenant == 0) {
                    Tenant::where('id', $oldTenantId)->update([
                        'status' => 'free',
                    ]);
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
>>>>>>> feb1f02 (first commit)
        }
    }

    public function view(Contract $contract)
    {
<<<<<<< HEAD
        if (!$contract->contract_file) abort(404);
        $path = public_path($contract->contract_file);
        if (!file_exists($path)) abort(404);

        return response()->file($path,[
            'Content-Type'=>'application/pdf',
            'Content-Disposition'=>'inline; filename="'.basename($path).'"'
=======
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
>>>>>>> feb1f02 (first commit)
        ]);
    }

    public function destroy(Contract $contract)
    {
<<<<<<< HEAD
        DB::beginTransaction();
        try {
            $tenantId = (int)$contract->tenant_id;
            $roomId   = (int)$contract->room_id;

            $contract->delete();

            // tenant -> free nếu không còn active
            $stillActiveTenant = Contract::where('tenant_id',$tenantId)->where('status','active')->count();
            if ($stillActiveTenant == 0) {
                Tenant::where('id',$tenantId)->update(['status'=>'free']);
            }

            // room -> empty nếu không còn active (trừ maintenance)
            $room = Room::find($roomId);
            if ($room && $room->status !== 'maintenance') {
                $stillActiveRoom = Contract::where('room_id',$roomId)->where('status','active')->count();
                if ($stillActiveRoom == 0) {
                    $room->update(['status'=>'empty']);
=======
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
                Tenant::where('id', $tenantId)->update([
                    'status' => 'free',
                ]);
            }

            $room = Room::find($roomId);

            if ($room && $room->status !== 'maintenance') {
                $stillActiveRoom = Contract::where('room_id', $roomId)
                    ->where('status', 'active')
                    ->count();

                if ($stillActiveRoom == 0) {
                    $room->update([
                        'status' => 'empty',
                    ]);
>>>>>>> feb1f02 (first commit)
                }
            }

            DB::commit();
<<<<<<< HEAD
            return back()->with('success','Đã xóa hợp đồng');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()]);
=======

            return back()->with('success', 'Đã xóa hợp đồng');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
>>>>>>> feb1f02 (first commit)
        }
    }
}