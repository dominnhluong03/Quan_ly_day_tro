<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Contract;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;

class TenantContractController extends Controller
{
    public function index()
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

        $contracts = Contract::with(['room'])
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('id')
            ->get();

        return view('tenant.contracts.index', compact('contracts', 'tenant'));
    }

    public function view(Contract $contract)
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

        if ((int) $contract->tenant_id !== (int) $tenant->id) {
            abort(403, 'Bạn không có quyền xem hợp đồng này.');
        }

        if (!$contract->contract_file) {
            $contract->contract_file = $this->generatePdf($contract->id);
            $contract->save();
        }

        $relative = str_replace('storage/', '', $contract->contract_file);

        if (!Storage::disk('public')->exists($relative)) {
            $contract->contract_file = $this->generatePdf($contract->id);
            $contract->save();
            $relative = str_replace('storage/', '', $contract->contract_file);
        }

        $path = Storage::disk('public')->path($relative);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function renew(Request $request, Contract $contract)
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

        if ((int) $contract->tenant_id !== (int) $tenant->id) {
            abort(403, 'Bạn không có quyền gia hạn hợp đồng này.');
        }

        if ($contract->status !== 'active') {
            return back()->withErrors([
                'error' => 'Chỉ có thể gia hạn hợp đồng đang hoạt động.',
            ]);
        }

        $data = $request->validate([
            'end_date' => 'required|date|after_or_equal:' . $contract->start_date,
        ], [
            'end_date.required' => 'Vui lòng chọn ngày kết thúc mới.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu hợp đồng.',
        ]);

        if ($contract->end_date && $data['end_date'] < $contract->end_date) {
            return back()->withErrors([
                'error' => 'Ngày gia hạn phải lớn hơn hoặc bằng ngày kết thúc hiện tại.',
            ])->withInput();
        }

        $contract->update([
            'end_date' => $data['end_date'],
        ]);

        $contract->contract_file = $this->generatePdf($contract->id);
        $contract->save();

        return back()->with('success', 'Gia hạn hợp đồng thành công!');
    }

    private function generatePdf(int $contractId): string
    {
        $contract = Contract::with(['tenant.user', 'room'])->findOrFail($contractId);

        $pdf = Pdf::loadView('admin.contracts.pdf', compact('contract'))->setPaper('a4');

        $filename = 'contracts/contract_' . $contract->id . '.pdf';

        if (!Storage::disk('public')->exists('contracts')) {
            Storage::disk('public')->makeDirectory('contracts');
        }

        Storage::disk('public')->put($filename, $pdf->output());

        return 'storage/' . $filename;
    }
}