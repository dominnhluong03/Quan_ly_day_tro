<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa phòng</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, .1);
            border-color: #6366f1;
        }

        .form-card {
            animation: slideUp .5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
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
            border-radius: 8px;
            color: #475569;
            font-weight: 700;
            cursor: pointer;
            margin-right: 12px;
        }
    </style>
</head>
<body class="p-6 md:p-12">

@php
    $assetNames = $room->assets?->pluck('name')->toArray() ?? [];
    $selectedAssets = old('assets', $assetNames);
    $deleteImageIds = old('delete_image_ids', []);

    $list = ['Điều hòa', 'Nóng lạnh', 'Wifi', 'Giường', 'Tủ lạnh', 'Máy giặt'];
@endphp

<div class="max-w-3xl mx-auto">
    <div class="mb-10">
        <a href="{{ route('admin.rooms.index') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm">
            ← Quay lại
        </a>

        <h2 class="text-3xl font-black text-slate-800 mt-3">
            Chỉnh sửa phòng
        </h2>

        <p class="text-slate-500 text-sm">
            Mã phòng: <b>#{{ $room->room_code }}</b> — ID: {{ $room->id }}
        </p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 form-card">
        <div class="p-8 md:p-12">

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                    <div class="mb-2 font-bold">Vui lòng kiểm tra lại:</div>
                    <ul class="list-disc ml-5 font-semibold space-y-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.rooms.update', $room->id) }}"
                  enctype="multipart/form-data"
                  class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Mã phòng
                        </label>

                        <input type="text"
                               name="room_code"
                               value="{{ old('room_code', $room->room_code) }}"
                               required
                               class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('room_code') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('room_code')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Thuộc tòa nhà
                        </label>

                        <select name="building_id"
                                required
                                class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('building_id') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">
                            <option value="">-- Chọn tòa nhà --</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b->id }}" {{ old('building_id', $room->building_id) == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('building_id')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Giá thuê (VNĐ/tháng)
                        </label>

                        <input type="number"
                               name="price"
                               value="{{ old('price', $room->price) }}"
                               required
                               min="0"
                               class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('price') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('price')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Diện tích (m²)
                        </label>

                        <input type="number"
                               step="0.1"
                               name="area"
                               value="{{ old('area', $room->area) }}"
                               min="0"
                               class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('area') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('area')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Số người tối đa
                        </label>

                        <input type="number"
                               name="max_people"
                               value="{{ old('max_people', $room->max_people) }}"
                               min="1"
                               class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('max_people') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('max_people')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Tầng
                        </label>

                        <input type="number"
                               name="floor"
                               value="{{ old('floor', $room->floor) }}"
                               min="0"
                               class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('floor') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">

                        @error('floor')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 uppercase">
                            Trạng thái
                        </label>

                        <select name="status"
                                class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('status') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">
                            <option value="empty" {{ old('status', $room->status) == 'empty' ? 'selected' : '' }}>
                                Phòng trống
                            </option>
                            <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>
                                Đang thuê
                            </option>
                            <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>
                                Đang bảo trì
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase">
                        Tiện nghi phòng
                    </label>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($list as $item)
                            <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 transition cursor-pointer">
                                <input type="checkbox"
                                       name="assets[]"
                                       value="{{ $item }}"
                                       class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                       {{ in_array($item, $selectedAssets) ? 'checked' : '' }}>
                                <span class="text-sm font-bold text-slate-700">{{ $item }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase">
                        Ảnh hiện có
                    </label>

                    @if($room->images && $room->images->count())
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($room->images as $img)
                                <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                                    <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                             alt="Ảnh phòng"
                                             class="w-full h-28 object-cover hover:scale-105 transition-transform">
                                    </a>

                                    <label class="flex items-center gap-2 px-3 py-3 bg-slate-50 border-t border-slate-100 cursor-pointer">
                                        <input type="checkbox"
                                               name="delete_image_ids[]"
                                               value="{{ $img->id }}"
                                               class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                                               {{ in_array($img->id, $deleteImageIds) ? 'checked' : '' }}>
                                        <span class="text-xs font-bold text-rose-600">Xóa ảnh này</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-[11px] text-slate-400 font-semibold">
                            * Tick vào ảnh nào thì khi bấm lưu, ảnh đó sẽ bị xóa.
                        </p>
                    @else
                        <p class="text-slate-400 font-bold text-sm">Chưa có ảnh</p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase">
                        Thêm ảnh mới
                    </label>

                    <input type="file"
                           name="images[]"
                           multiple
                           accept="image/*"
                           class="w-full px-6 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring">

                    @error('images')
                        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror

                    @error('images.*')
                        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase">
                        Mô tả
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="w-full px-6 py-4 border-2 rounded-2xl font-bold outline-none focus-ring {{ $errors->has('description') ? 'border-rose-500 bg-rose-50' : 'border-slate-200' }}">{{ old('description', $room->description) }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4 pt-8 border-t">
                    <a href="{{ route('admin.rooms.index') }}"
                       class="px-8 py-4 text-slate-400 font-bold">
                        Hủy
                    </a>

                    <button type="submit"
                            class="px-12 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-indigo-600 transition">
                        Lưu
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>