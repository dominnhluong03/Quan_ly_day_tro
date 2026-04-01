@extends('admin.layout')

@section('title','Quản lý hợp đồng')
@section('page_title','Hợp đồng thuê phòng')

@section('content')

<style>
    @keyframes modalSpring {
<<<<<<< HEAD
        0% { opacity: 0; transform: scale(0.9) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal { animation: modalSpring 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }
    .custom-table-scroll::-webkit-scrollbar {
        height: 6px;
    }
    .custom-table-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
=======
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal { animation: modalSpring 0.3s ease-out; }

    .modal-scroll::-webkit-scrollbar { width: 5px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .custom-table-scroll::-webkit-scrollbar { height: 6px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.45s ease-out; }
>>>>>>> feb1f02 (first commit)
</style>

<div class="max-w-[1400px] mx-auto">

<<<<<<< HEAD
    {{-- ALERT --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm animate-fadeIn">
        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm">✓</span>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Quản lý hợp đồng</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Pháp lý và thời hạn thuê phòng</p>
=======
    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 animate-fadeIn">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 animate-fadeIn">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Quản lý hợp đồng</h2>
            <p class="text-slate-500 text-sm">Pháp lý và thời hạn thuê phòng</p>
>>>>>>> feb1f02 (first commit)
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
<<<<<<< HEAD
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" id="tableSearch" placeholder="Tìm tên khách, mã phòng..." 
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>
            <button onclick="openModal()"
                class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-200 transition-all active:scale-95">
                <span>➕</span> Thêm hợp đồng
=======
                <input type="text" id="tableSearch" placeholder="Tìm tên khách, mã phòng..."
                       class="pl-4 pr-4 py-2.5 w-64 bg-white border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
            </div>
            <button onclick="openModal()"
                    class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm hợp đồng
>>>>>>> feb1f02 (first commit)
            </button>
        </div>
    </div>

<<<<<<< HEAD
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm transition-transform hover:scale-[1.02]">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest text-center">Tổng hợp đồng</p>
            <h3 class="text-3xl font-black mt-1 text-slate-800 text-center">{{ $contracts->count() }}</h3>
            <div class="w-8 h-1 bg-slate-100 mx-auto mt-3 rounded-full"></div>
        </div>

        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-100 text-white transition-transform hover:scale-[1.02]">
            <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest text-center">Đang hiệu lực</p>
            <h3 class="text-3xl font-black mt-1 text-center">{{ $contracts->where('status','active')->count() }}</h3>
            <div class="w-8 h-1 bg-white/20 mx-auto mt-3 rounded-full"></div>
        </div>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5">Khách & Phòng</th>
                        <th class="px-8 py-5">Thời hạn</th>
                        <th class="px-8 py-5 text-center">Đặt cọc</th>
                        <th class="px-8 py-5 text-center">Trạng thái</th>
                        <th class="px-8 py-5 text-center">Tệp tin</th>
                        <th class="px-8 py-5 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($contracts as $c)
                    <tr class="group hover:bg-slate-50/80 transition-all search-item">
                        {{-- Tenant & Room --}}
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-700 tenant-name">{{ $c->tenant?->user?->name ?? 'N/A' }}</span>
                                <span class="text-[11px] font-bold text-indigo-500 uppercase room-code">Phòng: {{ $c->room?->room_code ?? 'N/A' }}</span>
                            </div>
                        </td>

                        {{-- Dates --}}
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-slate-600">
                                <span>{{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }}</span>
                                <span class="mx-1 text-slate-300">→</span>
                                <span>{{ $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') : '---' }}</span>
                            </div>
                        </td>

                        {{-- Deposit --}}
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-black text-slate-800">
                                {{ number_format($c->deposit ?? 0) }}<small class="ml-0.5 text-[10px] text-slate-400">đ</small>
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-8 py-5 text-center">
                            @if($c->status === 'active')
                                <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">Hiệu lực</span>
                            @elseif($c->status === 'expired')
                                <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase">Hết hạn</span>
                            @else
                                <span class="px-3 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase">Hủy</span>
                            @endif
                        </td>

                        {{-- Contract Files --}}
                        <td class="px-8 py-5 text-center">
                            @if($c->contract_file)
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.contracts.view', $c->id) }}" target="_blank" 
                                       class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Xem PDF">
                                        📄
                                    </a>
                                    <a href="{{ asset($c->contract_file) }}" download
                                       class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all" title="Tải xuống">
                                        📥
                                    </a>
                                </div>
                            @else
                                <span class="text-[10px] font-bold text-slate-300 uppercase">Chưa có</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.contracts.edit', $c->id) }}"
                                   class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                                    ✏️
                                </a>
                                <form method="POST" action="{{ route('admin.contracts.destroy', $c->id) }}" onsubmit="return confirm('Xác nhận xóa hợp đồng này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-bold">🚫 Chưa có dữ liệu hợp đồng</p>
                        </td>
                    </tr>
=======
    <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tổng hợp đồng</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $contracts->count() }}</h3>
        </div>

        <div class="bg-indigo-600 p-6 rounded-xl text-white text-center shadow-lg shadow-indigo-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Đang hiệu lực</p>
            <h3 class="text-3xl font-black">{{ $contracts->where('status','active')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full">
                <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Khách & Phòng</th>
                        <th class="px-6 py-4 text-left">Thời hạn</th>
                        <th class="px-6 py-4 text-center">Đặt cọc</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-center">Tệp tin</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($contracts as $c)
                        @php
                            $isLiquidation = in_array($c->status, ['expired', 'cancelled']);
                            $viewRoute = null;
                            $downloadFile = null;
                            $fileLabel = null;

                            if ($isLiquidation && !empty($c->liquidation_file)) {
                                $viewRoute = route('admin.contracts.viewLiquidation', $c->id);
                                $downloadFile = asset($c->liquidation_file);
                                $fileLabel = 'PDF thanh lý';
                            } elseif (!empty($c->contract_file)) {
                                $viewRoute = route('admin.contracts.view', $c->id);
                                $downloadFile = asset($c->contract_file);
                                $fileLabel = 'PDF hợp đồng';
                            }
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors search-item">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <b class="text-slate-700 tenant-name">{{ $c->tenant?->user?->name ?? 'N/A' }}</b>
                                    <span class="text-[11px] font-bold text-indigo-500 uppercase room-code">
                                        Phòng: {{ $c->room?->room_code ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-slate-600">
                                    <span>{{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }}</span>
                                    <span class="mx-1 text-slate-300">→</span>
                                    <span>{{ $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') : '---' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-black text-slate-800">
                                    {{ number_format($c->deposit ?? 0) }}
                                    <small class="ml-0.5 text-[10px] text-slate-400">đ</small>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($c->status === 'active')
                                    <span class="font-bold text-xs text-emerald-600">Hiệu lực</span>
                                @elseif($c->status === 'expired')
                                    <span class="font-bold text-xs text-slate-500">Hết hạn</span>
                                @else
                                    <span class="font-bold text-xs text-rose-600">Hủy</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($viewRoute && $downloadFile)
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-[10px] font-bold uppercase {{ $isLiquidation ? 'text-orange-500' : 'text-indigo-500' }}">
                                            {{ $fileLabel }}
                                        </span>

                                        <div class="flex justify-center gap-2">
                                            <a href="{{ $viewRoute }}" target="_blank"
                                               class="px-3 py-2 rounded-lg {{ $isLiquidation ? 'bg-orange-50 text-orange-700 hover:bg-orange-100' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }} font-bold text-sm transition"
                                               title="Xem PDF">
                                                📄 Xem
                                            </a>

                                            <a href="{{ $downloadFile }}" download
                                               class="px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-sm hover:bg-emerald-100 transition"
                                               title="Tải xuống">
                                                📥 Tải
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase">Chưa có</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.contracts.edit', $c->id) }}"
                                       class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">
                                        ✏️ Sửa
                                    </a>

                                    <form method="POST" action="{{ route('admin.contracts.destroy', $c->id) }}"
                                          onsubmit="return confirm('Xác nhận xóa hợp đồng này?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-bold text-sm hover:bg-rose-100 transition">
                                            🗑 Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">Chưa có dữ liệu hợp đồng</td>
                        </tr>
>>>>>>> feb1f02 (first commit)
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<<<<<<< HEAD
{{-- MODAL THÊM HỢP ĐỒNG --}}
<div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl animate-modal overflow-hidden">
        <div class="p-8 md:p-10">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">Tạo hợp đồng mới</h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">Thiết lập các điều khoản thuê phòng</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.contracts.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Khách thuê</label>
                        <select name="tenant_id" required class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
=======
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">

        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Tạo hợp đồng mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thiết lập điều khoản thuê phòng</p>
            </div>
            <button onclick="closeModal()"
                    class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">✕</button>
        </div>

        <div class="p-6 overflow-y-auto modal-scroll">
            <form method="POST" action="{{ route('admin.contracts.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Khách thuê</label>
                        <select name="tenant_id" required
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
>>>>>>> feb1f02 (first commit)
                            <option value="">-- Chọn khách --</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->user?->name }}</option>
                            @endforeach
                        </select>
                    </div>
<<<<<<< HEAD
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Phòng thuê</label>
                        <select name="room_id" required class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->room_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày bắt đầu</label>
                        <input type="date" name="start_date" required class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày kết thúc</label>
                        <input type="date" name="end_date" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Giá điện (vnđ)</label>
                        <input type="number" name="electric_price" placeholder="4000" required class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Giá nước (vnđ)</label>
                        <input type="number" name="water_price" placeholder="30000" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tiền đặt cọc (VNĐ)</label>
                    <input type="number" name="deposit" required placeholder="5000000" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ghi chú dịch vụ</label>
                    <textarea name="service_note" rows="2" placeholder="Wifi, rác, xe..." class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none"></textarea>
                </div>

                <div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-slate-50">
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all">Lưu & Xuất PDF</button>
                    <button type="button" onclick="closeModal()" class="px-8 py-4 bg-transparent text-slate-400 font-bold text-sm hover:text-slate-600 transition-all">Đóng</button>
                </div>
=======

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Phòng thuê</label>
                        <select name="room_id" id="roomSelect" required
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $r)
                                @php
                                    $activeContracts = $r->contracts->map(function($c) {
                                        return [
                                            'id' => $c->id,
                                            'tenant_name' => $c->tenant?->user?->name ?? 'N/A',
                                            'start_date' => $c->start_date ? \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') : '---',
                                            'end_date' => $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') : '---',
                                            'deposit' => number_format($c->deposit ?? 0) . ' đ',
                                            'electric_price' => number_format($c->electric_price ?? 0) . ' đ',
                                            'water_price' => number_format($c->water_price ?? 0) . ' đ',
                                            'service_note' => $c->service_note ?? '',
                                        ];
                                    })->values();
                                @endphp

                                <option value="{{ $r->id }}"
                                    data-room-code="{{ $r->room_code }}"
                                    data-max-people="{{ $r->max_people ?? 1 }}"
                                    data-active-count="{{ $r->active_count ?? 0 }}"
                                    data-contracts='@json($activeContracts)'>
                                    {{ $r->room_code }} ({{ $r->active_count }}/{{ $r->max_people ?? 1 }} người)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="roomContractPreview" class="hidden p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-black text-sm text-slate-700">📋 Thông tin hợp đồng hiện tại của phòng</p>
                        <span id="roomCapacityText" class="text-xs font-bold text-indigo-600"></span>
                    </div>

                    <div id="roomContractList" class="space-y-3"></div>

                    <div id="roomNoContract" class="hidden text-sm font-bold text-slate-400">
                        Phòng này hiện chưa có hợp đồng active nào.
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày bắt đầu</label>
                        <input type="date" name="start_date" required
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày kết thúc</label>
                        <input type="date" name="end_date"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá điện (VNĐ)</label>
                        <input type="number" name="electric_price" required placeholder="4000"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá nước (VNĐ)</label>
                        <input type="number" name="water_price" placeholder="30000"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tiền đặt cọc (VNĐ)</label>
                    <input type="number" name="deposit" required placeholder="5000000"
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border border-transparent focus:border-indigo-500 outline-none">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Ghi chú dịch vụ</label>
                    <textarea name="service_note" rows="2" placeholder="Wifi, rác, xe..."
                              class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none"></textarea>
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                    <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu & Xuất PDF
                    </button>
                    <button type="button" onclick="closeModal()" class="px-8 text-slate-400 font-bold hover:text-slate-600">
                        Đóng
                    </button>
                </div>

>>>>>>> feb1f02 (first commit)
            </form>
        </div>
    </div>
</div>

<script>
<<<<<<< HEAD
    // Search Functionality
=======
>>>>>>> feb1f02 (first commit)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.search-item');

<<<<<<< HEAD
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            rows.forEach(row => {
                const tenant = row.querySelector('.tenant-name').textContent.toLowerCase();
                const room = row.querySelector('.room-code').textContent.toLowerCase();
                if (tenant.includes(query) || room.includes(query)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });

    // Modal Controls
    function openModal() {
        const m = document.getElementById('modal');
        m.classList.replace('hidden', 'flex');
    }
    function closeModal() {
        const m = document.getElementById('modal');
        m.classList.replace('flex', 'hidden');
    }

    // Đóng modal khi click ra ngoài
    window.onclick = function(e) {
        const m = document.getElementById('modal');
        if (e.target === m) closeModal();
    }
=======
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                rows.forEach(row => {
                    const tenant = row.querySelector('.tenant-name')?.textContent.toLowerCase() || '';
                    const room = row.querySelector('.room-code')?.textContent.toLowerCase() || '';
                    row.style.display = (tenant.includes(query) || room.includes(query)) ? '' : 'none';
                });
            });
        }

        const roomSelect = document.getElementById('roomSelect');
        const preview = document.getElementById('roomContractPreview');
        const list = document.getElementById('roomContractList');
        const noContract = document.getElementById('roomNoContract');
        const capacityText = document.getElementById('roomCapacityText');

        if (roomSelect) {
            roomSelect.addEventListener('change', function() {
                const selected = roomSelect.options[roomSelect.selectedIndex];

                if (!selected || !selected.value) {
                    preview.classList.add('hidden');
                    list.innerHTML = '';
                    noContract.classList.add('hidden');
                    capacityText.textContent = '';
                    return;
                }

                const roomCode = selected.dataset.roomCode || '';
                const maxPeople = selected.dataset.maxPeople || '1';
                const activeCount = selected.dataset.activeCount || '0';
                const contracts = JSON.parse(selected.dataset.contracts || '[]');

                preview.classList.remove('hidden');
                capacityText.textContent = `Phòng ${roomCode}: ${activeCount}/${maxPeople} người`;

                list.innerHTML = '';

                if (!contracts.length) {
                    noContract.classList.remove('hidden');
                    return;
                }

                noContract.classList.add('hidden');

                contracts.forEach((contract) => {
                    const item = document.createElement('div');
                    item.className = 'p-4 bg-white rounded-xl border border-slate-200';

                    item.innerHTML = `
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-black text-slate-800">Hợp đồng #${contract.id}</p>
                            <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 font-black uppercase">
                                Active
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-slate-400 font-bold">Khách thuê:</span>
                                <div class="font-bold text-slate-700">${contract.tenant_name}</div>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold">Tiền cọc:</span>
                                <div class="font-bold text-slate-700">${contract.deposit}</div>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold">Bắt đầu:</span>
                                <div class="font-bold text-slate-700">${contract.start_date}</div>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold">Kết thúc:</span>
                                <div class="font-bold text-slate-700">${contract.end_date}</div>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold">Giá điện:</span>
                                <div class="font-bold text-slate-700">${contract.electric_price}</div>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold">Giá nước:</span>
                                <div class="font-bold text-slate-700">${contract.water_price}</div>
                            </div>
                        </div>

                        ${
                            contract.service_note
                            ? `<div class="mt-3 text-xs text-slate-500">
                                   <span class="font-bold">Ghi chú:</span> ${contract.service_note}
                               </div>`
                            : ''
                        }
                    `;

                    list.appendChild(item);
                });
            });
        }
    });

    function openModal(){
        const m = document.getElementById('modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(){
        const m = document.getElementById('modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(e){
        if(e.target && e.target.id === 'modal') closeModal();
    }

    @if($errors->any())
        openModal();
    @endif
>>>>>>> feb1f02 (first commit)
</script>

@endsection