@extends('admin.layout')

@section('title','Thanh toán')
@section('page_title','Thanh toán')

@section('content')

<style>
@keyframes modalSpring {
    0% { opacity: 0; transform: scale(0.95) translateY(10px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal { animation: modalSpring 0.3s ease-out; }
</style>

<div class="max-w-[1400px] mx-auto">

    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-black">Quản lý thanh toán</h2>
            <p class="text-slate-500 text-sm">Xem biên lai tenant upload & xác nhận thanh toán</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.payments.index', ['tab' => 'pending']) }}"
               class="px-4 py-2 rounded-xl font-bold text-sm {{ $tab==='pending' ? 'bg-slate-900 text-white' : 'bg-white border text-slate-700' }}">
                Chờ duyệt
            </a>
            <a href="{{ route('admin.payments.index', ['tab' => 'approved']) }}"
               class="px-4 py-2 rounded-xl font-bold text-sm {{ $tab==='approved' ? 'bg-slate-900 text-white' : 'bg-white border text-slate-700' }}">
                Đã duyệt
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border text-center">
            <p class="text-slate-400 text-xs font-bold uppercase">Tổng biên lai</p>
            <h3 class="text-3xl font-black">{{ $payments->count() }}</h3>
        </div>
        <div class="bg-amber-500 p-6 rounded-xl text-white text-center">
            <p class="text-xs font-bold uppercase">Chờ duyệt</p>
            <h3 class="text-3xl font-black">{{ $payments->where('status','pending')->count() }}</h3>
        </div>
        <div class="bg-emerald-600 p-6 rounded-xl text-white text-center">
            <p class="text-xs font-bold uppercase">Đã duyệt</p>
            <h3 class="text-3xl font-black">{{ $payments->where('status','approved')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4">Phòng</th>
                    <th class="px-6 py-4">Khách</th>
                    <th class="px-6 py-4">Kỳ hóa đơn</th>
                    <th class="px-6 py-4 text-center">Trạng thái</th>
                    <th class="px-6 py-4 text-center">Biên lai</th>
                    <th class="px-6 py-4 text-right">Xác nhận</th>
                </tr>
            </thead>

            <tbody>
                @forelse($payments as $p)
                    @php
                        $inv = $p->invoice;
                        $roomCode = $inv?->contract?->room?->room_code ?? 'N/A';
                        $tenantName = $inv?->contract?->tenant?->user?->name ?? 'N/A';
                        $period = $inv ? str_pad($inv->month,2,'0',STR_PAD_LEFT).'/'.$inv->year : 'N/A';
                    @endphp
                    <tr class="border-t hover:bg-slate-50">
                        <td class="px-6 py-4 font-black">Phòng: {{ $roomCode }}</td>
                        <td class="px-6 py-4">{{ $tenantName }}</td>
                        <td class="px-6 py-4">{{ $period }}</td>

                        <td class="px-6 py-4 text-center">
                            @if($p->status === 'approved')
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black">
                                    Đã duyệt
                                </span>
                                <div class="text-[11px] text-slate-500 mt-1">
                                    {{ $p->approved_at ? \Carbon\Carbon::parse($p->approved_at)->format('d/m/Y H:i') : '' }}
                                </div>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-black">
                                    Chờ duyệt
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($p->image_path)
                                <a href="{{ route('admin.payments.view', $p->id) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-bold">
                                   👁 Xem
                                </a>
                                <a href="{{ route('admin.payments.download', $p->id) }}"
                                   class="ml-2 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm font-bold">
                                   ⬇ Tải
                                </a>
                            @else
                                <span class="text-slate-400 text-sm">Chưa có</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($p->status === 'approved')
                                <span class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 font-bold text-sm">
                                    ✅ Đã xác nhận
                                </span>
                            @else
                                <form method="POST" action="{{ route('admin.payments.approve', $p->id) }}"
                                      onsubmit="return confirm('Xác nhận thanh toán? Hóa đơn sẽ chuyển sang ĐÃ THANH TOÁN.');"
                                      class="inline">
                                    @csrf
                                    <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700">
                                        ✔ Xác nhận
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.payments.reject', $p->id) }}" class="inline"
                                    onsubmit="return confirm('Từ chối chứng từ này?')">
                                    @csrf
                                    <input type="hidden" name="note" value="Admin từ chối chứng từ">
                                    <button class="px-3 py-2 rounded-lg bg-rose-600 text-white font-bold text-sm">
                                        ❌ Từ chối
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400">Chưa có biên lai thanh toán</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection