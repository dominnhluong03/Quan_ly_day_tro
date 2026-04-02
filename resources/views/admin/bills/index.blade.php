@extends('admin.layout')

@section('title','Quản lý hóa đơn')
@section('page_title','Hóa đơn')

@section('content')

<style>
@keyframes modalSpring {
    0% { opacity: 0; transform: scale(0.95) translateY(10px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal { animation: modalSpring 0.3s ease-out; }
=======
@section('title', 'Quản lý hóa đơn')
@section('page_title', 'Hóa đơn')

@section('content')
@php
    $fmtVnd = fn($n) => number_format((int)($n ?? 0)) . ' đ';
@endphp

<style>
    @keyframes modalSpring {
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

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-black">Quản lý hóa đơn</h2>
        <p class="text-slate-500 text-sm">Tiền phòng, điện nước và dịch vụ</p>
    </div>

    <button onclick="openModal()"
        class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition">
        ➕ Tạo hóa đơn theo phòng
    </button>
</div>

<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border text-center">
        <p class="text-slate-400 text-xs font-bold uppercase">Tổng hóa đơn</p>
        <h3 class="text-3xl font-black">{{ $invoices->count() }}</h3>
    </div>

    <div class="bg-indigo-600 p-6 rounded-xl text-white text-center">
        <p class="text-xs font-bold uppercase">Chưa thanh toán</p>
        <h3 class="text-3xl font-black">{{ $invoices->where('status','unpaid')->count() }}</h3>
    </div>

    <div class="bg-emerald-600 p-6 rounded-xl text-white text-center">
        <p class="text-xs font-bold uppercase">Đã thanh toán</p>
        <h3 class="text-3xl font-black">{{ $invoices->where('status','paid')->count() }}</h3>
    </div>
</div>

<div class="bg-white rounded-xl border overflow-hidden">
<table class="w-full">
    <thead class="bg-slate-100 text-xs uppercase text-slate-500">
    <tr>
        <th class="px-6 py-4">Phòng</th>
        <th class="px-6 py-4">Kỳ</th>
        <th class="px-6 py-4 text-center">Tổng tiền</th>
        <th class="px-6 py-4 text-center">Trạng thái</th>
        <th class="px-6 py-4 text-center">Hóa đơn</th>
        <th class="px-6 py-4 text-right">Thao tác</th>
    </tr>
    </thead>

    <tbody>
    @forelse($invoices as $i)
    <tr class="border-t hover:bg-slate-50">
        <td class="px-6 py-4">
            <b>Phòng: {{ $i->contract?->room?->room_code ?? '' }}</b><br>
        </td>

        <td class="px-6 py-4 text-sm">
            {{ str_pad($i->month, 2, '0', STR_PAD_LEFT) }}/{{ $i->year }}
        </td>

        <td class="px-6 py-4 text-center font-black">
            {{ number_format($i->total ?? 0) }} đ
        </td>

        <td class="px-6 py-4 text-center">
            @if($i->status === 'paid')
                <span class="text-emerald-600 font-bold">Đã thanh toán</span>
            @else
                <span class="text-rose-600 font-bold">Chưa thanh toán</span>
            @endif
        </td>

        <td class="px-6 py-4 text-center">
            @if($i->invoice_file)
                <a href="{{ route('admin.bills.view', $i->id) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition">
                    📄 Xem
                </a>
                <a href="{{ asset($i->invoice_file) }}"
                   download
                   class="ml-2 inline-flex items-center gap-1 px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition">
                    ⬇️ Tải
                </a>
            @else
                <span class="text-gray-400 text-sm">Chưa có</span>
            @endif
        </td>

        <td class="px-6 py-4 text-right">
            <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.bills.edit', $i->id) }}"
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">
                    ✏️ Sửa
                </a>

                <form method="POST"
                      action="{{ route('admin.bills.destroy', $i->id) }}"
                      onsubmit="return confirm('Xóa hóa đơn?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-bold text-sm hover:bg-rose-100 transition">
                        🗑 Xóa
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="text-center py-10 text-gray-400">Chưa có hóa đơn</td>
    </tr>
    @endforelse
    </tbody>
</table>
</div>

</div>

{{-- MODAL --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[90vh] flex flex-col">

        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-xl font-black">Tạo hóa đơn theo phòng</h3>
            <button onclick="closeModal()" class="text-slate-400 font-black text-xl">✕</button>
        </div>

        <div class="p-6 overflow-y-auto">
            <form method="POST" action="{{ route('admin.bills.store') }}" class="space-y-4" id="billForm">
                @csrf

                <div>
                    <label class="text-xs font-bold">Chọn phòng</label>
                    <select name="room_id" id="roomSelect" required class="w-full p-3 bg-slate-50 rounded-xl">
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
            <h2 class="text-2xl font-black text-slate-900">Quản lý hóa đơn</h2>
            <p class="text-slate-500 text-sm">Tiền phòng, điện nước và dịch vụ</p>
        </div>

        <button type="button"
                onclick="BillsUI.openModal()"
                class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
            ➕ Tạo hóa đơn theo phòng
        </button>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tổng hóa đơn</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $invoices->count() }}</h3>
        </div>

        <div class="bg-indigo-600 p-6 rounded-xl text-white text-center shadow-lg shadow-indigo-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Chưa thanh toán</p>
            <h3 class="text-3xl font-black">{{ $invoices->where('status','unpaid')->count() }}</h3>
        </div>

        <div class="bg-emerald-600 p-6 rounded-xl text-white text-center shadow-lg shadow-emerald-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Đã thanh toán</p>
            <h3 class="text-3xl font-black">{{ $invoices->where('status','paid')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full">
                <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Phòng</th>
                        <th class="px-6 py-4 text-left">Kỳ</th>
                        <th class="px-6 py-4 text-center">Tổng tiền</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-center">Hóa đơn</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($invoices as $i)
                        @php
                            $roomCode = $i->contract?->room?->room_code ?? '';
                            $period   = str_pad((int)$i->month, 2, '0', STR_PAD_LEFT) . '/' . (int)$i->year;
                            $isPaid   = ($i->status === 'paid');
                        @endphp

                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-black text-slate-700">Phòng: {{ $roomCode ?: 'N/A' }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-600">{{ $period }}</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-black text-slate-800">
                                    {{ $fmtVnd($i->total) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($isPaid)
                                    <span class="font-bold text-xs text-emerald-600">Đã thanh toán</span>
                                @else
                                    <span class="font-bold text-xs text-rose-600">Chưa thanh toán</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($i->invoice_file)
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.bills.view', $i->id) }}"
                                           target="_blank"
                                           class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">
                                            📄 Xem
                                        </a>
                                        <a href="{{ asset($i->invoice_file) }}"
                                           download
                                           class="px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-sm hover:bg-emerald-100 transition">
                                            📥 Tải
                                        </a>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-300 uppercase">Chưa có</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    @if(!$isPaid)
                                        <a href="{{ route('admin.bills.edit', $i->id) }}"
                                           class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">
                                            ✏️ Sửa
                                        </a>
                                    @else
                                        <span class="px-3 py-2 rounded-lg bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed">
                                            🔒 Đã thanh toán
                                        </span>
                                    @endif

                                    <form method="POST"
                                          action="{{ route('admin.bills.destroy', $i->id) }}"
                                          onsubmit="return confirm('Xóa hóa đơn?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-bold text-sm hover:bg-rose-100 transition">
                                            🗑 Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">Chưa có hóa đơn</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TẠO HÓA ĐƠN --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">

        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Tạo hóa đơn mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thiết lập chi phí phòng</p>
            </div>
            <button type="button"
                    onclick="BillsUI.closeModal()"
                    class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                ✕
            </button>
        </div>

        <div class="p-6 overflow-y-auto modal-scroll">
            <form method="POST"
                  action="{{ route('admin.bills.store') }}"
                  class="space-y-5"
                  id="billForm">
                @csrf

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Phòng thuê</label>
                    <select name="room_id" id="roomSelect" required
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
>>>>>>> feb1f02 (first commit)
                        <option value="">-- Chọn phòng --</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}"
                                data-roomprice="{{ (int)$r->active_room_price }}"
                                data-eprice="{{ (int)$r->active_electric_price }}"
                                data-wprice="{{ (int)$r->active_water_price }}"
<<<<<<< HEAD
                            >
=======
                                data-electricold="{{ (int)$r->last_electric_new }}"
                                data-waterold="{{ (int)$r->last_water_new }}">
>>>>>>> feb1f02 (first commit)
                                {{ $r->room_code }} (max_people: {{ $r->max_people }})
                            </option>
                        @endforeach
                    </select>
<<<<<<< HEAD
                    <p class="text-[10px] text-slate-400 ml-1 italic">
                        * Giá điện/nước tự lấy từ hợp đồng active của phòng.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold">Tháng</label>
                        <input type="number" name="month" min="1" max="12" required
                               class="w-full p-3 bg-slate-50 rounded-xl" placeholder="VD: 2">
                    </div>
                    <div>
                        <label class="text-xs font-bold">Năm</label>
                        <input type="number" name="year" min="2000" required
                               class="w-full p-3 bg-slate-50 rounded-xl" placeholder="VD: 2026">
=======
                    <p class="mt-1 text-[11px] text-slate-400 italic">
                        * Nếu phòng đã có hóa đơn trước đó, chỉ số điện nước cũ sẽ tự lấy từ chỉ số mới của hóa đơn gần nhất.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tháng</label>
                        <input type="number" name="month" min="1" max="12" required placeholder="2"
                               value="{{ old('month') }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Năm</label>
                        <input type="number" name="year" min="2000" required placeholder="2026"
                               value="{{ old('year') }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tiền phòng (VNĐ)</label>
                        <input type="number" id="roomPrice" min="0" readonly value="0"
                               class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border border-transparent">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tổng tiền (VNĐ)</label>
                        <input type="text" id="totalText" readonly value="0 đ"
                               class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá điện (VNĐ)</label>
                        <input type="number" id="electricPrice" readonly value="0"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giá nước (VNĐ)</label>
                        <input type="number" id="waterPrice" readonly value="0"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Điện</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="electric_old" id="electricOld" min="0" placeholder="Cũ"
                                   value="{{ old('electric_old') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                            <input type="number" name="electric_new" id="electricNew" min="0" placeholder="Mới"
                                   value="{{ old('electric_new') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                        </div>
                        <p class="text-xs font-bold text-slate-500">
                            Số kWh: <span id="electricUsage">0</span> —
                            Tiền điện: <span class="text-indigo-600" id="electricCostText">0</span> đ
                        </p>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Nước</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" name="water_old" id="waterOld" min="0" placeholder="Cũ"
                                   value="{{ old('water_old') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                            <input type="number" name="water_new" id="waterNew" min="0" placeholder="Mới"
                                   value="{{ old('water_new') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                        </div>
                        <p class="text-xs font-bold text-slate-500">
                            Số m³: <span id="waterUsage">0</span> —
                            Tiền nước: <span class="text-indigo-600" id="waterCostText">0</span> đ
                        </p>
>>>>>>> feb1f02 (first commit)
                    </div>
                </div>

                <div>
<<<<<<< HEAD
                    <label class="text-xs font-bold">Tiền phòng (tự fill)</label>
                    <input type="number" id="roomPrice" min="0" readonly
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-slate-700" value="0">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold">Giá điện (lấy từ hợp đồng)</label>
                        <input type="number" id="electricPrice" min="0" readonly
                               class="w-full p-3 bg-slate-100 rounded-xl font-black text-slate-700" value="0">
                    </div>
                    <div>
                        <label class="text-xs font-bold">Giá nước (lấy từ hợp đồng)</label>
                        <input type="number" id="waterPrice" min="0" readonly
                               class="w-full p-3 bg-slate-100 rounded-xl font-black text-slate-700" value="0">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="font-black text-sm mb-3">⚡ Điện</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold">Cũ</label>
                                <input type="number" name="electric_old" id="electricOld" min="0"
                                       class="w-full p-3 bg-white rounded-xl">
                            </div>
                            <div>
                                <label class="text-xs font-bold">Mới</label>
                                <input type="number" name="electric_new" id="electricNew" min="0"
                                       class="w-full p-3 bg-white rounded-xl">
                            </div>
                        </div>
                        <div class="mt-3 text-sm font-bold text-slate-600">
                            Số kWh: <span id="electricUsage">0</span> — Tiền điện:
                            <span class="text-indigo-600" id="electricCostText">0</span> đ
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="font-black text-sm mb-3">💧 Nước</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold">Cũ</label>
                                <input type="number" name="water_old" id="waterOld" min="0"
                                       class="w-full p-3 bg-white rounded-xl">
                            </div>
                            <div>
                                <label class="text-xs font-bold">Mới</label>
                                <input type="number" name="water_new" id="waterNew" min="0"
                                       class="w-full p-3 bg-white rounded-xl">
                            </div>
                        </div>
                        <div class="mt-3 text-sm font-bold text-slate-600">
                            Số m³: <span id="waterUsage">0</span> — Tiền nước:
                            <span class="text-indigo-600" id="waterCostText">0</span> đ
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3 sticky top-0 bg-slate-50 py-1">
                        <p class="font-black text-sm">🧾 Dịch vụ khác</p>
                        <button type="button" onclick="addServiceRow()"
                                class="px-3 py-2 rounded-lg bg-white border font-bold text-sm hover:bg-slate-100">
=======
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-xs font-bold text-slate-500 block">Ghi chú / dịch vụ phát sinh</label>
                        <button type="button"
                                onclick="BillsCalc.addServiceRow()"
                                class="px-3 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200 transition">
>>>>>>> feb1f02 (first commit)
                            ➕ Thêm
                        </button>
                    </div>

<<<<<<< HEAD
                    <div id="serviceRows" class="space-y-2 max-h-48 overflow-y-auto pr-1"></div>

                    <div class="mt-3 text-sm font-bold text-slate-600">
                        Tổng dịch vụ: <span class="text-indigo-600" id="serviceSumText">0</span> đ
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold">Tổng tiền (tự tính)</label>
                    <input type="text" id="totalText" readonly
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-slate-700" value="0 đ">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Lưu & Xuất PDF
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="px-6 text-gray-500 font-bold">
                        Hủy
                    </button>
                </div>
            </form>
        </div>

=======
                    <div id="serviceRows" class="space-y-2"></div>

                    <p class="mt-3 text-xs font-bold text-slate-500">
                        Tổng dịch vụ: <span class="text-indigo-600" id="serviceSumText">0</span> đ
                    </p>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit"
                            class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-100">
                        Lưu & Xuất PDF
                    </button>
                    <button type="button"
                            onclick="BillsUI.closeModal()"
                            class="px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition-colors">
                        Đóng
                    </button>
                </div>

            </form>
        </div>
>>>>>>> feb1f02 (first commit)
    </div>
</div>

<script>
<<<<<<< HEAD
function openModal(){
    const m = document.getElementById('modal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal(){
    const m = document.getElementById('modal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
window.onclick = function(e){
    if(e.target && e.target.id === 'modal'){
        closeModal();
    }
}

function toInt(v){
    const n = parseInt(v || 0, 10);
    return isNaN(n) ? 0 : n;
}
function formatVND(n){
    try { return new Intl.NumberFormat('vi-VN').format(n) + ' đ'; }
    catch(e){ return n + ' đ'; }
}

function calcAll(){
    const roomPrice = toInt(document.getElementById('roomPrice').value);
    const ePrice = toInt(document.getElementById('electricPrice').value);
    const wPrice = toInt(document.getElementById('waterPrice').value);

    const eOld = toInt(document.getElementById('electricOld').value);
    const eNew = toInt(document.getElementById('electricNew').value);
    const wOld = toInt(document.getElementById('waterOld').value);
    const wNew = toInt(document.getElementById('waterNew').value);

    const eUsage = Math.max(0, eNew - eOld);
    const wUsage = Math.max(0, wNew - wOld);

    const eCost = eUsage * ePrice;
    const wCost = wUsage * wPrice;

    document.getElementById('electricUsage').innerText = eUsage;
    document.getElementById('waterUsage').innerText = wUsage;

    document.getElementById('electricCostText').innerText = new Intl.NumberFormat('vi-VN').format(eCost);
    document.getElementById('waterCostText').innerText = new Intl.NumberFormat('vi-VN').format(wCost);

    let serviceSum = 0;
    document.querySelectorAll('.service-price').forEach(ip => serviceSum += toInt(ip.value));
    document.getElementById('serviceSumText').innerText = new Intl.NumberFormat('vi-VN').format(serviceSum);

    const total = roomPrice + eCost + wCost + serviceSum;
    document.getElementById('totalText').value = formatVND(total);
}

function addServiceRow(name = '', price = ''){
    const wrap = document.getElementById('serviceRows');
    const row = document.createElement('div');
    row.className = "grid grid-cols-12 gap-2";

    row.innerHTML = `
        <div class="col-span-7">
            <input type="text" name="service_name[]"
                   class="w-full p-3 bg-white rounded-xl font-bold text-slate-700"
                   placeholder="VD: Wifi / rác / gửi xe..." value="${name}">
        </div>
        <div class="col-span-4">
            <input type="number" name="service_price[]"
                   class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 service-price"
                   placeholder="VD: 50000" min="0" value="${price}">
        </div>
        <div class="col-span-1 flex items-center justify-end">
            <button type="button"
                class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-black hover:bg-rose-100">✕</button>
        </div>
    `;

    row.querySelector('button').addEventListener('click', () => { row.remove(); calcAll(); });
    row.querySelectorAll('input').forEach(ip => ip.addEventListener('input', calcAll));

    wrap.appendChild(row);
    calcAll();
}

document.addEventListener('DOMContentLoaded', () => {
    addServiceRow();

    const roomSelect = document.getElementById('roomSelect');
    roomSelect.addEventListener('change', () => {
        const opt = roomSelect.options[roomSelect.selectedIndex];
        document.getElementById('roomPrice').value = opt.dataset.roomprice || 0;
        document.getElementById('electricPrice').value = opt.dataset.eprice || 0;
        document.getElementById('waterPrice').value = opt.dataset.wprice || 0;
        calcAll();
    });

    ['electricOld','electricNew','waterOld','waterNew']
        .forEach(id => document.getElementById(id).addEventListener('input', calcAll));

    calcAll();
});
</script>

=======
const BillsUI = {
    openModal() {
        const m = document.getElementById('modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        const m = document.getElementById('modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    },
    bindBackdropClose() {
        window.addEventListener('click', (e) => {
            if (e.target && e.target.id === 'modal') BillsUI.closeModal();
        });
    }
};

const BillsCalc = (() => {
    const $ = (id) => document.getElementById(id);

    const toInt = (v) => {
        const n = parseInt(v || 0, 10);
        return Number.isNaN(n) ? 0 : n;
    };

    const formatVND = (n) => {
        try { return new Intl.NumberFormat('vi-VN').format(n) + ' đ'; }
        catch (e) { return n + ' đ'; }
    };

    const recalc = () => {
        const roomPrice = toInt($('roomPrice').value);
        const ePrice = toInt($('electricPrice').value);
        const wPrice = toInt($('waterPrice').value);

        const eOld = toInt($('electricOld').value);
        const eNew = toInt($('electricNew').value);
        const wOld = toInt($('waterOld').value);
        const wNew = toInt($('waterNew').value);

        const eUsage = Math.max(0, eNew - eOld);
        const wUsage = Math.max(0, wNew - wOld);

        const eCost = eUsage * ePrice;
        const wCost = wUsage * wPrice;

        $('electricUsage').innerText = eUsage;
        $('waterUsage').innerText = wUsage;
        $('electricCostText').innerText = new Intl.NumberFormat('vi-VN').format(eCost);
        $('waterCostText').innerText = new Intl.NumberFormat('vi-VN').format(wCost);

        let serviceSum = 0;
        document.querySelectorAll('.service-price').forEach(ip => serviceSum += toInt(ip.value));
        $('serviceSumText').innerText = new Intl.NumberFormat('vi-VN').format(serviceSum);

        const total = roomPrice + eCost + wCost + serviceSum;
        $('totalText').value = formatVND(total);
    };

    const addServiceRow = (name = '', price = '') => {
        const wrap = $('serviceRows');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-2';

        row.innerHTML = `
            <div class="col-span-7">
                <input type="text" name="service_name[]"
                       class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none"
                       placeholder="VD: Wifi / rác / gửi xe..." value="${name}">
            </div>
            <div class="col-span-4">
                <input type="number" name="service_price[]"
                       class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 service-price border border-transparent focus:border-indigo-500 outline-none"
                       placeholder="50000" min="0" value="${price}">
            </div>
            <div class="col-span-1 flex items-center justify-end">
                <button type="button"
                        class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-bold hover:bg-rose-100 transition">
                    ✕
                </button>
            </div>
        `;

        row.querySelector('button').addEventListener('click', () => {
            row.remove();
            recalc();
        });

        row.querySelectorAll('input').forEach(ip => ip.addEventListener('input', recalc));

        wrap.appendChild(row);
        recalc();
    };

    const bindRoomSelect = () => {
        const roomSelect = $('roomSelect');

        roomSelect.addEventListener('change', () => {
            const opt = roomSelect.options[roomSelect.selectedIndex];

            $('roomPrice').value     = opt?.dataset?.roomprice || 0;
            $('electricPrice').value = opt?.dataset?.eprice || 0;
            $('waterPrice').value    = opt?.dataset?.wprice || 0;

            // Chỉ số cũ của hóa đơn mới = chỉ số mới của hóa đơn trước
            $('electricOld').value   = opt?.dataset?.electricold || 0;
            $('waterOld').value      = opt?.dataset?.waterold || 0;

            recalc();
        });
    };

    const bindMeterInputs = () => {
        ['electricOld', 'electricNew', 'waterOld', 'waterNew'].forEach(id => {
            $(id).addEventListener('input', recalc);
        });
    };

    const init = () => {
        addServiceRow();
        bindRoomSelect();
        bindMeterInputs();
        recalc();
    };

    return { init, recalc, addServiceRow };
})();

document.addEventListener('DOMContentLoaded', () => {
    BillsUI.bindBackdropClose();
    BillsCalc.init();
});
</script>
>>>>>>> feb1f02 (first commit)
@endsection