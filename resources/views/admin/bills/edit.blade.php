<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa hóa đơn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
<<<<<<< HEAD
        .focus-ring:focus { box-shadow: 0 0 0 4px rgba(99,102,241,.1); border-color: #6366f1; }
=======
        .focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
>>>>>>> feb1f02 (first commit)
    </style>
</head>
<body class="p-6 md:p-12">

<<<<<<< HEAD
<div class="max-w-3xl mx-auto">

    <div class="mb-10">
        <a href="{{ route('admin.bills.index') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm">
            ← Quay lại
        </a>
        <h2 class="text-3xl font-black text-slate-800 mt-3">Chỉnh sửa hóa đơn</h2>
        <p class="text-slate-500 text-sm">#{{ $invoice->id }} — Phòng {{ $invoice->contract?->room?->room_code }}</p>
    </div>

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold">
=======
@php
    $room = $invoice->contract?->room;
    $tenant = $invoice->contract?->tenant?->user;
    $roomPrice = (int)($invoice->room_price ?? 0);
    $electricCost = (int)($invoice->electric_cost ?? 0);
    $waterCost = (int)($invoice->water_cost ?? 0);
    $serviceTotal = (int)($invoice->service_total ?? 0);
    $total = (int)($invoice->total ?? 0);

    $oldNames = old('service_name', $invoice->services->pluck('service_name')->toArray());
    $oldPrices = old('service_price', $invoice->services->pluck('price')->toArray());
@endphp

<div class="max-w-4xl mx-auto">

    <div class="mb-10">
        <a href="{{ route('admin.bills.index') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm transition-all">
            ← Quay lại
        </a>

        <h2 class="text-3xl font-black text-slate-800 mt-3">Chỉnh sửa hóa đơn</h2>
        <p class="text-slate-500 text-sm mt-1">
            #{{ $invoice->id }} — Phòng {{ $room?->room_code ?? 'N/A' }}
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100">
>>>>>>> feb1f02 (first commit)
            ❌ {{ $errors->first() }}
        </div>
    @endif

<<<<<<< HEAD
    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-8 md:p-12">

            <form method="POST" action="{{ route('admin.bills.update', $invoice->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold">Tháng</label>
                        <input type="number" name="month" min="1" max="12"
                               value="{{ old('month', $invoice->month) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl focus-ring">
                    </div>
                    <div>
                        <label class="text-xs font-bold">Năm</label>
                        <input type="number" name="year" min="2000"
                               value="{{ old('year', $invoice->year) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl focus-ring">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold">Điện cũ</label>
                        <input type="number" name="electric_old" id="electricOld"
                               value="{{ old('electric_old', $invoice->meter?->electric_old ?? 0) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl focus-ring">
                    </div>
                    <div>
                        <label class="text-xs font-bold">Điện mới</label>
                        <input type="number" name="electric_new" id="electricNew"
                               value="{{ old('electric_new', $invoice->meter?->electric_new ?? 0) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl focus-ring">
                    </div>

                    <div>
                        <label class="text-xs font-bold">Nước cũ</label>
                        <input type="number" name="water_old" id="waterOld"
                               value="{{ old('water_old', $invoice->meter?->water_old ?? 0) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl focus-ring">
                    </div>
                    <div>
                        <label class="text-xs font-bold">Nước mới</label>
                        <input type="number" name="water_new" id="waterNew"
                               value="{{ old('water_new', $invoice->meter?->water_new ?? 0) }}"
                               class="w-full p-3 bg-slate-50 rounded-xl focus-ring">
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-black text-sm">🧾 Dịch vụ khác</p>
                        <button type="button" onclick="addServiceRow()" class="px-3 py-2 rounded-lg bg-white border font-bold text-sm">
=======
    {{-- thông tin nhanh --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Phòng</p>
            <p class="mt-2 text-lg font-black text-slate-800">{{ $room?->room_code ?? 'N/A' }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Người thuê</p>
            <p class="mt-2 text-sm font-black text-slate-800">{{ $tenant?->name ?? 'N/A' }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Tổng hiện tại</p>
            <p class="mt-2 text-lg font-black text-indigo-700">{{ number_format($total) }} đ</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Trạng thái</p>
            <p class="mt-2 text-sm font-black {{ $invoice->status === 'paid' ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $invoice->status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-8 md:p-12">

            <form method="POST" action="{{ route('admin.bills.update', $invoice->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- kỳ hóa đơn --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Tháng</label>
                        <input type="number"
                               name="month"
                               min="1"
                               max="12"
                               value="{{ old('month', $invoice->month) }}"
                               class="mt-2 w-full p-4 bg-slate-50 rounded-2xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Năm</label>
                        <input type="number"
                               name="year"
                               min="2000"
                               value="{{ old('year', $invoice->year) }}"
                               class="mt-2 w-full p-4 bg-slate-50 rounded-2xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                    </div>
                </div>

                {{-- chi phí hiện tại --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-4">
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Tiền phòng</p>
                        <p class="mt-2 text-lg font-black text-slate-800">{{ number_format($roomPrice) }} đ</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4">
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Tiền điện</p>
                        <p class="mt-2 text-lg font-black text-slate-800">{{ number_format($electricCost) }} đ</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4">
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Tiền nước</p>
                        <p class="mt-2 text-lg font-black text-slate-800">{{ number_format($waterCost) }} đ</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4">
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Dịch vụ</p>
                        <p class="mt-2 text-lg font-black text-slate-800">{{ number_format($serviceTotal) }} đ</p>
                    </div>
                </div>

                {{-- điện nước --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-50 rounded-2xl p-5 space-y-4">
                        <p class="font-black text-slate-800">⚡ Chỉ số điện</p>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-500">Điện cũ</label>
                                <input type="number"
                                       name="electric_old"
                                       id="electricOld"
                                       value="{{ old('electric_old', $invoice->meter?->electric_old ?? 0) }}"
                                       class="mt-2 w-full p-3 bg-white rounded-xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-500">Điện mới</label>
                                <input type="number"
                                       name="electric_new"
                                       id="electricNew"
                                       value="{{ old('electric_new', $invoice->meter?->electric_new ?? 0) }}"
                                       class="mt-2 w-full p-3 bg-white rounded-xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5 space-y-4">
                        <p class="font-black text-slate-800">💧 Chỉ số nước</p>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-500">Nước cũ</label>
                                <input type="number"
                                       name="water_old"
                                       id="waterOld"
                                       value="{{ old('water_old', $invoice->meter?->water_old ?? 0) }}"
                                       class="mt-2 w-full p-3 bg-white rounded-xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-500">Nước mới</label>
                                <input type="number"
                                       name="water_new"
                                       id="waterNew"
                                       value="{{ old('water_new', $invoice->meter?->water_new ?? 0) }}"
                                       class="mt-2 w-full p-3 bg-white rounded-xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- dịch vụ --}}
                <div class="bg-slate-50 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <p class="font-black text-slate-800">🧾 Dịch vụ khác</p>
                        <button type="button"
                                onclick="addServiceRow()"
                                class="px-3 py-2 rounded-lg bg-white border border-slate-200 font-bold text-sm hover:bg-slate-100 transition">
>>>>>>> feb1f02 (first commit)
                            ➕ Thêm
                        </button>
                    </div>

                    <div id="serviceRows" class="space-y-2">
<<<<<<< HEAD
                        @php
                            $oldNames = old('service_name', $invoice->services->pluck('service_name')->toArray());
                            $oldPrices = old('service_price', $invoice->services->pluck('price')->toArray());
                        @endphp

                        @for($k=0; $k < max(count($oldNames), count($oldPrices)); $k++)
=======
                        @for($k = 0; $k < max(count($oldNames), count($oldPrices)); $k++)
>>>>>>> feb1f02 (first commit)
                            @php
                                $n = $oldNames[$k] ?? '';
                                $p = $oldPrices[$k] ?? '';
                            @endphp
<<<<<<< HEAD
                            <div class="grid grid-cols-12 gap-2 service-row">
                                <div class="col-span-7">
                                    <input type="text" name="service_name[]" value="{{ $n }}"
                                           class="w-full p-3 bg-white rounded-xl font-bold text-slate-700">
                                </div>
                                <div class="col-span-4">
                                    <input type="number" name="service_price[]" value="{{ $p }}"
                                           class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 service-price" min="0">
                                </div>
                                <div class="col-span-1 flex items-center justify-end">
                                    <button type="button"
                                            class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-black"
                                            onclick="this.closest('.service-row').remove();">✕</button>
=======

                            <div class="grid grid-cols-12 gap-2 service-row">
                                <div class="col-span-7">
                                    <input type="text"
                                           name="service_name[]"
                                           value="{{ $n }}"
                                           class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none"
                                           placeholder="VD: Wifi">
                                </div>

                                <div class="col-span-4">
                                    <input type="number"
                                           name="service_price[]"
                                           value="{{ $p }}"
                                           class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 service-price border border-transparent focus:border-indigo-500 outline-none"
                                           min="0"
                                           placeholder="50000">
                                </div>

                                <div class="col-span-1 flex items-center justify-end">
                                    <button type="button"
                                            class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-black hover:bg-rose-100 transition"
                                            onclick="this.closest('.service-row').remove();">
                                        ✕
                                    </button>
>>>>>>> feb1f02 (first commit)
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

<<<<<<< HEAD
                <div>
                    <label class="text-xs font-bold">Trạng thái</label>
                    <select name="status" class="w-full p-3 bg-slate-50 rounded-xl focus-ring font-bold">
                        <option value="unpaid" @selected(old('status', $invoice->status)=='unpaid')>Chưa thanh toán</option>
                        <option value="paid" @selected(old('status', $invoice->status)=='paid')>Đã thanh toán</option>
                    </select>
                </div>

                <div class="pt-6 flex justify-end gap-4 border-t">
                    <a href="{{ route('admin.bills.index') }}" class="px-8 py-3 text-slate-400 font-bold">Hủy</a>
                    <button class="px-10 py-3 bg-slate-900 text-white rounded-xl font-black hover:bg-indigo-600">
                        Lưu & Cập nhật PDF
                    </button>
                </div>

=======
                {{-- trạng thái --}}
                <div>
                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Trạng thái thanh toán</label>
                    <select name="status"
                            class="mt-2 w-full p-4 bg-slate-50 rounded-2xl border-2 border-transparent font-bold text-slate-700 focus-ring outline-none">
                        <option value="unpaid" @selected(old('status', $invoice->status) == 'unpaid')>Chưa thanh toán</option>
                        <option value="paid" @selected(old('status', $invoice->status) == 'paid')>Đã thanh toán</option>
                    </select>
                </div>

                <div class="pt-8 flex justify-end gap-4 border-t border-slate-100">
                    <a href="{{ route('admin.bills.index') }}"
                       class="px-8 py-3 text-slate-400 font-bold hover:text-slate-600 transition">
                        Hủy
                    </a>

                    <button type="submit"
                            class="px-10 py-3 bg-slate-900 text-white rounded-xl font-black hover:bg-indigo-600 transition">
                        Lưu & Cập nhật PDF
                    </button>
                </div>
>>>>>>> feb1f02 (first commit)
            </form>

        </div>
    </div>
<<<<<<< HEAD

</div>

<script>
function addServiceRow(){
    const wrap = document.getElementById('serviceRows');
    const row = document.createElement('div');
    row.className = "grid grid-cols-12 gap-2 service-row";
    row.innerHTML = `
        <div class="col-span-7">
            <input type="text" name="service_name[]" class="w-full p-3 bg-white rounded-xl font-bold text-slate-700" placeholder="VD: wifi">
        </div>
        <div class="col-span-4">
            <input type="number" name="service_price[]" class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 service-price" min="0" placeholder="50000">
        </div>
        <div class="col-span-1 flex items-center justify-end">
            <button type="button" class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-black"
                    onclick="this.closest('.service-row').remove();">✕</button>
        </div>
    `;
=======
</div>

<script>
function addServiceRow() {
    const wrap = document.getElementById('serviceRows');
    const row = document.createElement('div');
    row.className = "grid grid-cols-12 gap-2 service-row";

    row.innerHTML = `
        <div class="col-span-7">
            <input type="text"
                   name="service_name[]"
                   class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none"
                   placeholder="VD: Wifi">
        </div>
        <div class="col-span-4">
            <input type="number"
                   name="service_price[]"
                   class="w-full p-3 bg-white rounded-xl font-bold text-slate-700 service-price border border-transparent focus:border-indigo-500 outline-none"
                   min="0"
                   placeholder="50000">
        </div>
        <div class="col-span-1 flex items-center justify-end">
            <button type="button"
                    class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-black hover:bg-rose-100 transition"
                    onclick="this.closest('.service-row').remove();">
                ✕
            </button>
        </div>
    `;

>>>>>>> feb1f02 (first commit)
    wrap.appendChild(row);
}
</script>

</body>
</html>