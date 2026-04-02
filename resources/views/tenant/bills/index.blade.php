@extends('tenant.layout')

@section('title', 'Hóa đơn & Thanh toán')
@section('page_title', 'Xem hóa đơn')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-6xl mx-auto pb-20 animate-fadeIn"
     x-data="{
        openProof: false,
        proofImg: ''
     }">

    @if(session('success'))
        <div class="mb-8 flex items-center gap-3 px-6 py-4 rounded-[2rem] bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm animate-slideDown">
            <span class="flex-shrink-0 w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-200">✓</span>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-8 flex items-center gap-3 px-6 py-4 rounded-[2rem] bg-rose-50 border border-rose-100 text-rose-700 shadow-sm animate-slideDown">
            <span class="flex-shrink-0 w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-rose-200">✕</span>
            <p class="font-bold text-sm">{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div class="space-y-1">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Hóa đơn dịch vụ</h1>
            <p class="text-slate-500 font-medium flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                Theo dõi lịch sử thanh toán và tải xuống chứng từ PDF
            </p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden mb-12">
        <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Hóa đơn cần xử lý</h2>
            <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-500">
                Tháng {{ date('m/Y') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="px-8 py-5">Phòng / Kỳ</th>
                        <th class="px-8 py-5 text-center">Tổng tiền</th>
                        <th class="px-8 py-5 text-center">Trạng thái</th>
                        <th class="px-8 py-5 text-center">Tệp tin</th>
                        <th class="px-8 py-5 text-right">Hành động thanh toán</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $i)
                        @php $payment = $i->latest_payment ?? null; @endphp
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">
                                        {{ substr($i->contract?->room?->room_code ?? 'R', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 tracking-tight">
                                            {{ $i->contract?->room?->room_code ?? '---' }}
                                        </p>
                                        <p class="text-[11px] font-bold text-slate-400">
                                            Kỳ: {{ str_pad($i->month, 2, '0', STR_PAD_LEFT) }}/{{ $i->year }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-6 text-center">
                                <span class="text-lg font-black text-slate-900 tracking-tighter">
                                    {{ number_format($i->total ?? 0) }}
                                    <span class="text-xs ml-1 text-slate-400 font-medium">đ</span>
                                </span>
                            </td>

                            <td class="px-8 py-6 text-center">
                                @if($i->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wide border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Đã thanh toán
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[10px] font-black uppercase tracking-wide border border-rose-200 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Chưa thanh toán
                                    </span>
                                @endif
                            </td>

                            <td class="px-8 py-6 text-center">
                                @if($i->invoice_file)
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('tenant.bills.view', $i->id) }}"
                                           target="_blank"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm"
                                           title="Xem PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('tenant.bills.download', $i->id) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all shadow-sm"
                                           title="Tải về">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter">Chưa có file</span>
                                @endif
                            </td>

                            <td class="px-8 py-6 text-right">
                                @if($i->status === 'paid')
                                    <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl italic">
                                        Giao dịch hoàn tất
                                    </span>
                                @elseif($payment && $payment->status === 'pending')
                                    <div class="inline-flex flex-col items-end gap-1">
                                        <span class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 font-black text-[10px] uppercase tracking-wider border border-amber-100">
                                            ⏳ Chờ duyệt
                                        </span>
                                        @if($payment->image_path)
                                            <button type="button"
                                                    @click="openProof = true; proofImg = '{{ asset('storage/' . $payment->image_path) }}'"
                                                    class="text-[10px] font-bold text-indigo-500 hover:underline tracking-tight">
                                                Xem chứng từ đã gửi
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <form method="POST"
                                          action="{{ route('tenant.bills.payment.store', $i->id) }}"
                                          enctype="multipart/form-data"
                                          class="flex flex-col md:flex-row items-center gap-2 justify-end">
                                        @csrf

                                        <div class="relative overflow-hidden bg-slate-100 px-3 py-1.5 rounded-xl border-2 border-dashed border-slate-200 hover:border-indigo-400 transition-colors">
                                            <input type="file" name="image" accept="image/*" required class="absolute inset-0 opacity-0 cursor-pointer">
                                            <span class="text-[10px] font-black text-slate-500 uppercase">Chọn ảnh</span>
                                        </div>

                                        <input type="text"
                                               name="note"
                                               placeholder="Ghi chú..."
                                               class="w-32 px-3 py-2 text-[11px] font-bold rounded-xl border border-slate-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">

                                        <button type="submit"
                                                class="px-5 py-2 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg active:scale-95">
                                            {{ ($payment && $payment->status === 'rejected') ? 'Gửi lại' : 'Gửi' }}
                                        </button>
                                    </form>

                                    @if($payment && $payment->status === 'rejected')
                                        <p class="text-[10px] text-rose-500 font-bold mt-1 italic">
                                            Lý do từ chối: {{ $payment->note }}
                                        </p>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center opacity-40">
                                <p class="text-lg font-bold">Bạn không có hóa đơn nào cần xử lý</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Lịch sử giao dịch</h2>
            <div class="h-[2px] flex-1 bg-slate-100 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $approvedInvoices = $invoices->filter(fn($inv) => $inv->status === 'paid');
            @endphp

            @forelse($approvedInvoices as $i)
                @php $payment = $i->latest_payment ?? null; @endphp
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl font-black italic">
                            {{ str_pad($i->month, 2, '0', STR_PAD_LEFT) }}/{{ $i->year }}
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Duyệt ngày</p>
                            <p class="text-xs font-bold text-slate-700 tracking-tight leading-none">
                                {{ $payment && $payment->approved_at ? \Carbon\Carbon::parse($payment->approved_at)->format('d/m/Y') : '---' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1 mb-6">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-tighter leading-none">
                            Phòng {{ $i->contract?->room?->room_code ?? '---' }}
                        </p>
                        <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none">
                            {{ number_format($i->total ?? 0) }} đ
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        @if($payment && $payment->image_path)
                            <button @click="openProof = true; proofImg = '{{ asset('storage/' . $payment->image_path) }}'"
                                    class="flex items-center gap-2 text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Chứng từ
                            </button>
                        @else
                            <span></span>
                        @endif

                        <span class="flex items-center gap-1 text-[10px] font-black text-emerald-500 uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Đã xác minh
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-10 text-center text-slate-400 font-bold italic tracking-tight">
                    Chưa có giao dịch nào được xác nhận
                </div>
            @endforelse
        </div>
    </div>

    <div x-show="openProof"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90 translate-y-10"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6"
         @keydown.escape.window="openProof = false">

        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity" @click="openProof = false"></div>

        <div class="relative z-[110] bg-white rounded-[3rem] p-4 shadow-2xl max-w-2xl w-full">
            <button @click="openProof = false"
                    class="absolute -top-12 right-0 text-white flex items-center gap-2 font-black uppercase text-[10px] tracking-widest">
                Đóng (Esc)
                <svg class="w-8 h-8 rounded-full bg-white/10 p-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="overflow-hidden rounded-[2.5rem] bg-slate-100 flex items-center justify-center">
                <img :src="proofImg" class="max-h-[70vh] w-auto object-contain">
            </div>

            <div class="px-6 py-4 text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Chứng từ thanh toán điện tử</p>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    body { background-color: #fcfdfe; -webkit-font-smoothing: antialiased; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.6s ease-out forwards;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-slideDown {
        animation: slideDown 0.4s ease-out forwards;
    }
</style>
@endsection