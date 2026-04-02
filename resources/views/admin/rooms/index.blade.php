@extends('admin.layout')

@section('title','Quản lý phòng')
@section('page_title','Hệ thống phòng cho thuê')

@section('content')
<style>
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    .animate-modal { animation: modalSpring 0.3s ease-out; }

    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    .custom-table-scroll::-webkit-scrollbar { height: 6px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .modal-scroll::-webkit-scrollbar { width: 6px; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn { animation: fadeIn 0.45s ease-out; }

    input[type="file"]::file-selector-button {
        background: #eef2ff;
        border: 0;
        padding: 8px 14px;
        border-radius: 999px;
        color: #4338ca;
        font-weight: 700;
        cursor: pointer;
        margin-right: 10px;
    }
</style>

<div class="max-w-[1400px] mx-auto">
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm animate-fadeIn">
            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm">✓</span>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 shadow-sm animate-fadeIn">
            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-sm">✕</span>
            <p class="font-bold text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Danh sách phòng</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Quản lý kho phòng và trạng thái vận hành</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input id="tableSearch" type="text" placeholder="Tìm mã phòng, tòa nhà..."
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>

            <button type="button" onclick="openCreateModal()"
                class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm phòng mới
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm text-center">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Tổng phòng</p>
            <h3 class="text-3xl font-black mt-1 text-slate-800">{{ $rooms->count() }}</h3>
        </div>

        <div class="bg-emerald-500 p-6 rounded-[2rem] shadow-lg shadow-emerald-100 text-white text-center">
            <p class="text-emerald-100 text-[10px] font-black uppercase tracking-widest">Phòng trống</p>
            <h3 class="text-3xl font-black mt-1">{{ $rooms->where('status', 'empty')->count() }}</h3>
        </div>

        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-100 text-white text-center">
            <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest">Đang thuê</p>
            <h3 class="text-3xl font-black mt-1">{{ $rooms->where('status', 'occupied')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5">Phòng</th>
                        <th class="px-8 py-5">Tòa nhà</th>
                        <th class="px-8 py-5 text-center">Tầng / DT</th>
                        <th class="px-8 py-5 text-center">Giá thuê</th>
                        <th class="px-8 py-5 text-center">Người ở</th>
                        <th class="px-8 py-5 text-center">Trạng thái</th>
                        <th class="px-8 py-5 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($rooms as $room)
                        <tr class="group hover:bg-slate-50/80 transition-all search-item">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-xl overflow-hidden bg-slate-100">
                                        @if($room->images && $room->images->count())
                                            <img src="{{ asset('storage/'.$room->images[0]->image_path) }}" class="w-full h-full object-cover" alt="{{ $room->room_code }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-slate-400">N/A</div>
                                        @endif
                                    </div>
                                    <span class="font-black text-slate-700 r-code">{{ $room->room_code }}</span>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <span class="text-slate-600 font-bold r-building">{{ $room->building->name ?? 'N/A' }}</span>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <div class="inline-flex flex-col">
                                    <span class="text-xs font-black text-slate-800">Tầng {{ $room->floor }}</span>
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase">{{ $room->area }} m²</span>
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center font-black text-slate-800">
                                {{ number_format($room->price) }}<small class="text-[10px] text-slate-400">đ</small>
                            </td>

                            <td class="px-8 py-5 text-center">
                                @php
                                    $current = $room->current_people ?? 0;
                                    $max = $room->max_people ?? 1;
                                @endphp
                                <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-full text-xs font-black {{ $current >= $max ? 'bg-rose-50 text-rose-600' : 'bg-indigo-50 text-indigo-600' }}">
                                    {{ $current }}/{{ $max }}
                                </span>
                            </td>

                            <td class="px-8 py-5 text-center">
                                @if($room->status === 'empty')
                                    <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">Trống</span>
                                @elseif($room->status === 'occupied')
                                    <span class="px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase">Đang thuê</span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase">Bảo trì</span>
                                @endif
                            </td>

                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                       class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">✏️</a>

                                    <form method="POST" action="{{ route('admin.rooms.destroy', $room->id) }}" class="inline" onsubmit="return confirm('Xác nhận xóa phòng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">🗑</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center">
                                <p class="text-slate-400 font-bold">🚫 Chưa có dữ liệu phòng</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TẠO PHÒNG --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[90vh] flex flex-col shadow-2xl">
        <div class="p-6 border-b flex items-center justify-between bg-white shrink-0">
            <div>
                <h3 class="text-xl font-black text-slate-900">Cấu hình phòng mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thông tin chi tiết tài sản</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">✕</button>
        </div>

        <div class="flex-1 overflow-y-auto modal-scroll p-6 pb-4">
            <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                @if($errors->any())
                    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                        <div class="font-bold text-rose-700 mb-2">Vui lòng kiểm tra lại thông tin:</div>
                        <ul class="list-disc pl-5 text-sm text-rose-600 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Mã số phòng</label>
                        <input name="room_code" value="{{ old('room_code') }}" required placeholder="VD: P.101"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border {{ $errors->has('room_code') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Thuộc tòa nhà</label>
                        <select name="building_id" required
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border {{ $errors->has('building_id') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                            <option value="">-- Chọn tòa nhà --</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b->id }}" {{ old('building_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div><input type="number" name="floor" value="{{ old('floor') }}" placeholder="Tầng" class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('floor') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none"></div>
                    <div><input type="number" step="0.1" name="area" value="{{ old('area') }}" placeholder="Diện tích" class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('area') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none"></div>
                    <div><input type="number" name="max_people" value="{{ old('max_people') }}" placeholder="Max người" class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('max_people') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none"></div>
                </div>

                <div>
                    <input type="number" name="price" value="{{ old('price') }}" required placeholder="Giá thuê tháng (VNĐ)"
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border {{ $errors->has('price') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                </div>

                <div>
                    <select name="status" class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('status') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                        <option value="empty" {{ old('status', 'empty') == 'empty' ? 'selected' : '' }}>Phòng trống</option>
                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Đang thuê</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                    </select>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="font-black text-sm mb-3 text-slate-700">🛋 Tiện nghi có sẵn</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['Điều hòa', 'Nóng lạnh', 'Wifi', 'Giường', 'Tủ lạnh', 'Máy giặt'] as $asset)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="assets[]" value="{{ $asset }}" class="w-4 h-4 rounded border-slate-300 text-indigo-600" {{ in_array($asset, old('assets', [])) ? 'checked' : '' }}>
                                <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600">{{ $asset }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Hình ảnh (Nhiều tệp)</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs font-bold text-slate-500 cursor-pointer">
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu thông tin phòng
                    </button>
                    <button type="button" onclick="closeCreateModal()" class="px-8 text-slate-400 font-bold hover:text-slate-600">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        const m = document.getElementById('modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal() {
        const m = document.getElementById('modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'modal') closeCreateModal();
    });

    document.getElementById('tableSearch')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.search-item').forEach(row => {
            const code = row.querySelector('.r-code')?.textContent.toLowerCase() || '';
            const bld = row.querySelector('.r-building')?.textContent.toLowerCase() || '';
            row.style.display = (code.includes(query) || bld.includes(query)) ? '' : 'none';
        });
    });

    @if($errors->any())
        openCreateModal();
    @endif
</script>
@endsection