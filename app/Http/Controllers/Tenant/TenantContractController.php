<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
=======
>>>>>>> feb1f02 (first commit)
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

        // chỉ xem hợp đồng của mình
        if ((int)$contract->tenant_id !== (int)$tenant->id) {
            abort(403, 'Bạn không có quyền xem hợp đồng này.');
        }

        if (!$contract->contract_file) {
<<<<<<< HEAD
            // nếu chưa có file => regen
=======
>>>>>>> feb1f02 (first commit)
            $contract->contract_file = $this->generatePdf($contract->id);
            $contract->save();
        }

<<<<<<< HEAD
        $relative = str_replace('storage/', '', $contract->contract_file); // contracts/contract_4.pdf

        // ✅ nếu file bị mất thật => regen lại
=======
        $relative = str_replace('storage/', '', $contract->contract_file);

        // nếu file bị mất thì tạo lại
>>>>>>> feb1f02 (first commit)
        if (!Storage::disk('public')->exists($relative)) {
            $contract->contract_file = $this->generatePdf($contract->id);
            $contract->save();
            $relative = str_replace('storage/', '', $contract->contract_file);
        }

        $path = Storage::disk('public')->path($relative);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
<<<<<<< HEAD
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

=======
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    /**
     * Gia hạn hợp đồng - tenant chỉ được sửa end_date
     */
    public function renew(Request $request, Contract $contract)
    {
        $tenant = Tenant::where('user_id', auth()->id())->firstOrFail();

        if ((int)$contract->tenant_id !== (int)$tenant->id) {
            abort(403, 'Bạn không có quyền gia hạn hợp đồng này.');
        }

        if ($contract->status !== 'active') {
            return back()->withErrors([
                'error' => 'Chỉ có thể gia hạn hợp đồng đang hoạt động.'
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
                'error' => 'Ngày gia hạn phải lớn hơn hoặc bằng ngày kết thúc hiện tại.'
            ])->withInput();
        }

        $contract->update([
            'end_date' => $data['end_date'],
        ]);

        $contract->contract_file = $this->generatePdf($contract->id);
        $contract->save();

        return back()->with('success', 'Gia hạn hợp đồng thành công!');
    }
>>>>>>> feb1f02 (first commit)

    private function generatePdf(int $contractId): string
    {
        $contract = Contract::with(['tenant.user', 'room'])->findOrFail($contractId);

<<<<<<< HEAD
        // ✅ dùng view PDF mới (bạn tạo ở bước 2)
=======
>>>>>>> feb1f02 (first commit)
        $pdf = Pdf::loadView('admin.contracts.pdf', compact('contract'))->setPaper('a4');

        $filename = "contracts/contract_{$contract->id}.pdf";

<<<<<<< HEAD
        // tạo thư mục nếu chưa có
=======
>>>>>>> feb1f02 (first commit)
        if (!Storage::disk('public')->exists('contracts')) {
            Storage::disk('public')->makeDirectory('contracts');
        }

        Storage::disk('public')->put($filename, $pdf->output());

<<<<<<< HEAD
        return 'storage/'.$filename; // dùng cho asset()
=======
        return 'storage/' . $filename;
>>>>>>> feb1f02 (first commit)
    }
}