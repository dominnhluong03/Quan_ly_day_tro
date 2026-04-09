@extends('admin.layout')

@section('title', 'Chỉnh sửa hợp đồng')
@section('page_title', 'Chỉnh sửa hợp đồng')

@section('content')
<style>
    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    .form-card {
        animation: slideUp .45s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    input[type="file"]::file-selector-button {
        background: #f1f5f9;
        border: none;
        padding: 8px 16px;
        border-radius: 12px;
        color: #475569;
        font-weight: 700;
        cursor: pointer;
        margin-right: 12px;
    }
</style>

<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.contracts.index') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm transition">
            ← Quay lại
        </a>

        <h2 class="text-3xl font-black text-slate-800 mt-3">
            Chỉnh sửa hợp đồng
        </h2>

        <p class="text-slate-500 text-sm">
            Hợp đồng #{{ $contract->id }}
            @if($contract->room)
                — Phòng: <b>{{ $contract->room->room_code }}</b>
            @endif
        </p>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 form-card">
        <div class="p-8 md:p-10">

            @if(session('success'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 px-5 py-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                    <div class="mb-2 font-bold">Vui lòng kiểm tra lại:</div>
                    <ul class="list-disc ml-5 space-y-1 font-semibold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.contracts.update', $contract->id) }}"
                  enctype="multipart/form-data"
                  class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Phòng
                        </label>

                        <select name="room_id"
                                required
                                class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('room_id') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', $contract->room_id) == $room->id ? 'selected' : '' }}>
                                    {{ $room->room_code }}
                                </option>
                            @endforeach
                        </select>

                        @error('room_id')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Trạng thái
                        </label>

                        <select name="status"
                                class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('status') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">
                            <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Đang hiệu lực</option>
                            <option value="expired" {{ old('status', $contract->status) == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                            <option value="cancelled" {{ old('status', $contract->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>

                        @error('status')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Ngày bắt đầu
                        </label>

                        <input type="date"
                               name="start_date"
                               value="{{ old('start_date', optional($contract->start_date)->format('Y-m-d') ?? \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d')) }}"
                               required
                               class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('start_date') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('start_date')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Ngày kết thúc
                        </label>

                        <input type="date"
                               name="end_date"
                               value="{{ old('end_date', $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') : '') }}"
                               class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('end_date') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('end_date')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Ghi chú hợp đồng
                        </label>

                        <textarea name="note"
                                  rows="4"
                                  class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('note') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}"
                                  placeholder="Nhập ghi chú thêm nếu có...">{{ old('note', $contract->note) }}</textarea>

                        @error('note')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t pt-8">
                    <h3 class="text-sm font-black text-slate-700 uppercase tracking-wider mb-4">
                        Thông tin dịch vụ
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">
                                Tiền điện
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="electric_price"
                                   value="{{ old('electric_price', $contract->electric_price) }}"
                                   class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('electric_price') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                            @error('electric_price')
                                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">
                                Tiền nước
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="water_price"
                                   value="{{ old('water_price', $contract->water_price) }}"
                                   class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('water_price') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                            @error('water_price')
                                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">
                                Tiền wifi
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="wifi_price"
                                   value="{{ old('wifi_price', $contract->wifi_price) }}"
                                   class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('wifi_price') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                            @error('wifi_price')
                                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase">
                                Phí dịch vụ khác
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="service_price"
                                   value="{{ old('service_price', $contract->service_price) }}"
                                   class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('service_price') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                            @error('service_price')
                                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase">
                                Diễn giải dịch vụ
                            </label>

                            <textarea name="service_note"
                                      rows="3"
                                      class="w-full px-5 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('service_note') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}"
                                      placeholder="Wifi, rác, gửi xe...">{{ old('service_note', $contract->service_note) }}</textarea>

                            @error('service_note')
                                <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t pt-8 space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            File hợp đồng hiện tại
                        </label>

                        @if($contract->contract_file)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $contract->contract_file) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-bold text-sm">
                                    Xem file hiện tại
                                </a>
                            </div>
                        @else
                            <p class="mt-2 text-sm font-bold text-slate-400">Chưa có file hợp đồng</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Tải file hợp đồng mới
                        </label>

                        <input type="file"
                               name="contract_file"
                               accept=".pdf,.doc,.docx,image/*"
                               class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring">

                        @error('contract_file')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8 border-t">
                    <a href="{{ route('admin.contracts.index') }}"
                       class="px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition">
                        Hủy bỏ
                    </a>

                    <button type="submit"
                            class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-indigo-600 transition">
                        Cập nhật hợp đồng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection