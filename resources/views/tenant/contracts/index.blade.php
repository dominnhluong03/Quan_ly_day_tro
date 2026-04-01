@extends('tenant.layout')

<<<<<<< HEAD
@section('title','Hợp đồng')
@section('page_title','Hợp đồng')

@section('content')

<div class="max-w-5xl mx-auto">

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

    <div class="bg-white rounded-2xl border overflow-hidden">
        <div class="px-6 py-5 border-b">
            <h2 class="text-xl font-black text-slate-800">Hợp đồng của bạn</h2>
            <p class="text-sm text-slate-500">Xem hợp đồng PDF của bạn</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Phòng</th>
                        <th class="px-6 py-4 text-left">Thời hạn</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-center">PDF</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($contracts as $c)
                    <tr class="border-t">
                        <td class="px-6 py-4 font-bold">
                            {{ $c->room?->room_code ?? ('#'.$c->room_id) }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            {{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }}
                            →
                            {{ $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') : '---' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($c->status === 'active')
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black">ACTIVE</span>
                            @elseif($c->status === 'expired')
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-black">EXPIRED</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-black">CANCELLED</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($c->contract_file)
                                <a href="{{ route('tenant.contracts.view', $c->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition">
                                    📄 Xem
                                </a>
                            @else
                                <span class="text-slate-400 text-sm">Chưa có</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                            Chưa có hợp đồng
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>
=======
@section('title', 'Quản lý Hợp đồng')
@section('page_title', 'Hợp đồng thuê')

@section('content')
<style>
    [x-cloak] { display: none !important; }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .table-container {
        overflow: hidden;
        border-radius: 1rem;
        border: 1px solid rgb(226 232 240);
        background: white;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }

    .modal-scroll::-webkit-scrollbar { width: 5px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-6xl mx-auto space-y-6 pb-20" x-data="{
    openRenew: false,
    renewId: null,
    roomCode: '',
    oldEndDate: '',
    newEndDate: '',
    isLoading: false,

    isValidDate() {
        if(!this.newEndDate || !this.oldEndDate) return false;
        return new Date(this.newEndDate) > new Date(this.oldEndDate);
    },
    
    diffDays() {
        if(!this.isValidDate()) return 0;
        const start = new Date(this.oldEndDate);
        const end = new Date(this.newEndDate);
        return Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    }
}">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="flex items-center p-4 mb-4 text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-100 animate-fade-in-down">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Danh sách hợp đồng</h1>
            <p class="text-slate-500 font-medium">Theo dõi thời hạn và quản lý tệp tin pháp lý</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-sm font-bold text-slate-600">
                Tổng số: {{ $contracts->count() }}
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="table-container">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] uppercase tracking-widest text-slate-400 font-black">
                    <th class="px-6 py-5">Mã Phòng</th>
                    <th class="px-6 py-5">Hiệu lực hợp đồng</th>
                    <th class="px-6 py-5 text-center">Trạng thái</th>
                    <th class="px-6 py-5 text-center">Tệp đính kèm</th>
                    <th class="px-6 py-5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($contracts as $c)
                <tr class="hover:bg-slate-50/40 transition-all duration-200 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold">
                                {{ substr($c->room?->room_code ?? 'R', 0, 1) }}
                            </div>
                            <span class="font-bold text-slate-700">{{ $c->room?->room_code ?? ('ID: '.$c->room_id) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-600">
                            {{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }} 
                            <span class="mx-2 text-slate-300">→</span>
                            <span class="{{ $c->status == 'expired' ? 'text-rose-500' : 'text-indigo-600 font-bold' }}">
                                {{ $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') : 'Vô thời hạn' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusClasses = [
                                'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'expired' => 'bg-rose-100 text-rose-700 border-rose-200',
                                'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200'
                            ];
                            $statusLabel = ['active' => 'Đang chạy', 'expired' => 'Hết hạn', 'cancelled' => 'Đã hủy'];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusClasses[$c->status] ?? $statusClasses['cancelled'] }}">
                            {{ $statusLabel[$c->status] ?? 'Không rõ' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($c->contract_file)
                            <a href="{{ route('tenant.contracts.view', $c->id) }}" target="_blank" 
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                        @else
                            <span class="text-slate-300 italic text-xs underline decoration-dotted">Chưa có file</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if(in_array($c->status, ['active', 'expired']))
                        <button @click="
                            openRenew = true;
                            renewId = {{ $c->id }};
                            roomCode = '{{ $c->room?->room_code }}';
                            oldEndDate = '{{ $c->end_date }}';
                            newEndDate = '';
                            isLoading = false;
                        " class="px-5 py-2 bg-white border-2 border-indigo-600 text-indigo-600 rounded-xl text-xs font-black hover:bg-indigo-600 hover:text-white transition-all shadow-sm active:scale-90">
                            GIA HẠN
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <p class="text-slate-400 font-medium tracking-tight">Bạn hiện chưa có dữ liệu hợp đồng nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL GIA HẠN --}}
    <div x-show="openRenew"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/40">

        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
             @click.away="openRenew = false">

            {{-- Header sticky --}}
            <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Gia hạn hợp đồng</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">
                        Phòng <span class="text-indigo-600" x-text="roomCode"></span>
                    </p>
                </div>
                <button type="button"
                        @click="openRenew = false"
                        class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                    ✕
                </button>
            </div>

            {{-- Body scroll --}}
            <div class="p-6 overflow-y-auto modal-scroll">
                <form :action="`{{ url('tenant/contracts') }}/${renewId}/renew`"
                      method="POST"
                      class="space-y-5"
                      @submit="isLoading = true">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Mã phòng</label>
                            <input type="text"
                                   :value="roomCode"
                                   readonly
                                   class="w-full p-3 bg-slate-100 rounded-xl font-bold text-slate-700 border border-transparent">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Hạn hiện tại</label>
                            <input type="text"
                                   :value="oldEndDate ? new Date(oldEndDate).toLocaleDateString('vi-VN') : ''"
                                   readonly
                                   class="w-full p-3 bg-slate-100 rounded-xl font-bold text-slate-700 border border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày hết hạn mới</label>
                        <input type="date"
                               name="end_date"
                               x-model="newEndDate"
                               :min="oldEndDate"
                               required
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div x-show="isValidDate()" x-cloak class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-600">Thời gian gia hạn thêm</p>
                            <p class="text-sm font-black text-indigo-600" x-text="diffDays() + ' ngày'"></p>
                        </div>
                    </div>

                    <div x-show="newEndDate && !isValidDate()" x-cloak class="p-4 bg-rose-50 rounded-xl border border-rose-200">
                        <p class="text-sm font-bold text-rose-600">
                            Ngày hết hạn mới phải lớn hơn ngày hết hạn hiện tại.
                        </p>
                    </div>

                    {{-- Footer sticky --}}
                    <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                        <button type="submit"
                                :disabled="!isValidDate() || isLoading"
                                class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <template x-if="isLoading">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="isLoading ? 'Đang xử lý...' : 'Xác nhận gia hạn'"></span>
                        </button>

                        <button type="button"
                                @click="openRenew = false"
                                class="px-8 text-slate-400 font-bold hover:text-slate-600">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

>>>>>>> feb1f02 (first commit)
@endsection