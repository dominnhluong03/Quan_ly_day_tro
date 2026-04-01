<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật hợp đồng | Hệ thống quản lý</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        .form-card { animation: slideUp 0.5s ease-out; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="p-6 md:p-12">

<div class="max-w-3xl mx-auto">

    <div class="mb-10">
        <a href="{{ route('admin.contracts.index') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm transition-all group">
            <span class="group-hover:-translate-x-1 transition-transform">←</span>
            Quay lại danh sách
        </a>
        <h2 class="text-3xl font-black text-slate-800 mt-3 tracking-tight">Chỉnh sửa hợp đồng</h2>
        <p class="text-slate-500 text-sm mt-1 font-medium">Mã hợp đồng #{{ $contract->id }}</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden form-card">
        <div class="p-8 md:p-12">

<<<<<<< HEAD
            {{-- Thông báo lỗi validate --}}
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold">
                    Vui lòng kiểm tra lại thông tin.
=======
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-100">
                    <div class="font-bold mb-2">Vui lòng kiểm tra lại thông tin:</div>
                    <ul class="list-disc pl-5 text-sm font-semibold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
>>>>>>> feb1f02 (first commit)
                </div>
            @endif

            <form method="POST" action="{{ route('admin.contracts.update', $contract->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<<<<<<< HEAD
                    {{-- Khách thuê (Cố định) --}}
=======
>>>>>>> feb1f02 (first commit)
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            Khách thuê
                            <span class="text-[9px] bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full normal-case">Thông tin cố định</span>
                        </label>
                        <div class="relative group">
                            <input type="text" value="{{ $contract->tenant?->user?->name ?? 'N/A' }}" readonly
                                   class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl font-bold text-slate-400 cursor-not-allowed outline-none">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">🔒</span>
                        </div>
                        <input type="hidden" name="tenant_id" value="{{ $contract->tenant_id }}">
                        @error('tenant_id')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

<<<<<<< HEAD
                    {{-- Chọn phòng (lọc theo max_people) --}}
=======
>>>>>>> feb1f02 (first commit)
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Số phòng thuê</label>

                        <div class="relative">
                            <select name="room_id" required
                                    class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all appearance-none cursor-pointer">

                                @foreach($rooms as $r)
                                    @php
                                        $activeCount = $activeCountsByRoom[$r->id] ?? 0;
<<<<<<< HEAD

                                        $isCurrentRoom = ($contract->room_id == $r->id);
                                        $countForCheck = $activeCount;

                                        if($isCurrentRoom && $contract->status === 'active'){
                                            $countForCheck = max(0, $activeCount - 1);
                                        }

                                        $maxPeople = $r->max_people ?? 1;
                                        $isFull = ($countForCheck >= $maxPeople);
                                    @endphp

                                    {{-- Luôn cho phép hiển thị phòng hiện tại, còn phòng khác thì phải còn chỗ --}}
=======
                                        $isCurrentRoom = ((int) $contract->room_id === (int) $r->id);
                                        $countForCheck = $activeCount;

                                        if ($isCurrentRoom && $contract->status === 'active') {
                                            $countForCheck = max(0, $activeCount - 1);
                                        }

                                        $maxPeople = (int) ($r->max_people ?? 1);
                                        $isFull = ($countForCheck >= $maxPeople);
                                    @endphp

>>>>>>> feb1f02 (first commit)
                                    @if($isCurrentRoom || !$isFull)
                                        <option value="{{ $r->id }}" @selected(old('room_id', $contract->room_id) == $r->id)>
                                            {{ $r->room_code ?? ('Phòng #' . $r->id) }}
                                            @if($isCurrentRoom)
                                                (Phòng hiện tại)
                                            @else
                                                — Còn trống {{ max(0, $maxPeople - $countForCheck) }} chỗ
                                            @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">▼</span>
                        </div>

                        <p class="text-[10px] text-slate-400 ml-1 italic">* Danh sách chỉ hiển thị các phòng còn đủ chỗ trống theo max_people.</p>
                        @error('room_id')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="h-px bg-slate-50"></div>

<<<<<<< HEAD
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày bắt đầu</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date) }}"
=======
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-px flex-1 bg-slate-100"></div>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Thời hạn hiện tại</p>
                        <div class="h-px flex-1 bg-slate-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày bắt đầu cũ</label>
                            <input type="text"
                                   readonly
                                   value="{{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '---' }}"
                                   class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl font-bold text-slate-500 cursor-not-allowed outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày kết thúc cũ</label>
                            <input type="text"
                                   readonly
                                   value="{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '---' }}"
                                   class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl font-bold text-slate-500 cursor-not-allowed outline-none">
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-50"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày bắt đầu mới</label>
                        <input type="date" name="start_date" value="{{ old('start_date', optional($contract->start_date)->format('Y-m-d')) }}"
>>>>>>> feb1f02 (first commit)
                               class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all">
                        @error('start_date')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
<<<<<<< HEAD
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày kết thúc</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date) }}"
=======
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày kết thúc mới</label>
                        <input type="date" name="end_date" value="{{ old('end_date', optional($contract->end_date)->format('Y-m-d')) }}"
>>>>>>> feb1f02 (first commit)
                               class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all">
                        @error('end_date')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tiền đặt cọc</label>
                        <div class="relative">
                            <input type="number" name="deposit" value="{{ old('deposit', $contract->deposit) }}"
                                   class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all pr-12">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 font-bold text-slate-300">đ</span>
                        </div>
                        @error('deposit')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Trạng thái hiện tại</label>
                        <div class="relative">
                            <select name="status"
                                    class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all appearance-none cursor-pointer">
                                <option value="active" @selected(old('status', $contract->status) == 'active')>Đang hiệu lực</option>
                                <option value="expired" @selected(old('status', $contract->status) == 'expired')>Hết hạn</option>
                                <option value="cancelled" @selected(old('status', $contract->status) == 'cancelled')>Hủy</option>
                            </select>

                            <span class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">▼</span>
                        </div>
                        @error('status')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

<<<<<<< HEAD
                    {{-- ✅ THÊM GIÁ ĐIỆN --}}
=======
>>>>>>> feb1f02 (first commit)
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Giá điện (đ/kWh)</label>
                        <div class="relative">
                            <input type="number" name="electric_price" value="{{ old('electric_price', $contract->electric_price) }}"
                                   class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all pr-12"
                                   placeholder="VD: 3500">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 font-bold text-slate-300">đ</span>
                        </div>
                        @error('electric_price')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

<<<<<<< HEAD
                    {{-- ✅ THÊM GIÁ NƯỚC --}}
=======
>>>>>>> feb1f02 (first commit)
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Giá nước (đ/m³)</label>
                        <div class="relative">
                            <input type="number" name="water_price" value="{{ old('water_price', $contract->water_price) }}"
                                   class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all pr-12"
                                   placeholder="VD: 15000">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 font-bold text-slate-300">đ</span>
                        </div>
                        @error('water_price')
                            <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>
<<<<<<< HEAD

                </div>

                {{-- Dịch vụ khác --}}
=======
                </div>

>>>>>>> feb1f02 (first commit)
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Dịch vụ khác</label>
                    <textarea name="service_note" rows="3"
                              class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all"
                              placeholder="Wifi, rác, gửi xe...">{{ old('service_note', $contract->service_note) }}</textarea>
                    @error('service_note')
                        <p class="text-rose-500 text-xs font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-10 flex flex-col md:flex-row items-center justify-end gap-4 border-t border-slate-50">
                    <a href="{{ route('admin.contracts.index') }}"
                       class="w-full md:w-auto px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition-all text-center">
                        Hủy bỏ
                    </a>

                    <button type="submit"
                            class="w-full md:w-auto px-12 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:-translate-y-1 active:scale-95 transition-all">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>