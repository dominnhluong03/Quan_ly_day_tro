@extends('admin.layout')

@section('title','Quản lý phòng')
@section('page_title','Hệ thống phòng cho thuê')

@section('content')

<<<<<<< HEAD
<style>
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.9) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal { animation: modalSpring 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }
    /* Style cho input file chuyên nghiệp hơn */
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

<div class="max-w-[1400px] mx-auto">
    
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
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Danh sách phòng</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Quản lý kho phòng và trạng thái vận hành</p>
=======
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="max-w-[1400px] mx-auto"
     x-data="{
        openModal: false,
        images: [],
        currentIndex: 0,

        openGallery(imgs, index = 0) {
            if (!imgs || imgs.length === 0) return;
            this.images = imgs;
            this.currentIndex = index;
            this.openModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeGallery() {
            this.openModal = false;
            document.body.style.overflow = 'auto';
        },
        nextImage() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        prevImage() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        }
     }">

    @if(session('success'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 animate-fadeIn">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 animate-fadeIn">
        ❌ {{ session('error') }}
    </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Quản lý phòng</h2>
            <p class="text-slate-500 text-sm">Kho phòng và trạng thái vận hành</p>
>>>>>>> feb1f02 (first commit)
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
<<<<<<< HEAD
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" id="tableSearch" placeholder="Tìm mã phòng, tòa nhà..." 
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>
            <button onclick="openModal()"
                class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-200 transition-all active:scale-95">
                <span>➕</span> Thêm phòng mới
=======
                <input type="text" id="tableSearch" placeholder="Tìm mã phòng..."
                    class="pl-4 pr-4 py-2.5 w-64 bg-white border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
            </div>
            <button onclick="openCreateModal()"
                class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm phòng mới
>>>>>>> feb1f02 (first commit)
            </button>
        </div>
    </div>

<<<<<<< HEAD
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        {{-- Tổng phòng - Màu trung tính --}}
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm transition-transform hover:scale-[1.02]">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest text-center">Tổng quy mô phòng</p>
            <h3 class="text-3xl font-black mt-1 text-slate-800 text-center">{{ $rooms->count() }}</h3>
            <div class="w-8 h-1 bg-slate-100 mx-auto mt-3 rounded-full"></div>
        </div>

        {{-- Phòng trống - Màu xanh lá (Tượng trưng cho doanh thu tiềm năng) --}}
        <div class="bg-emerald-500 p-6 rounded-[2rem] shadow-lg shadow-emerald-100 text-white transition-transform hover:scale-[1.02]">
            <p class="text-emerald-100 text-[10px] font-black uppercase tracking-widest text-center">Phòng đang trống</p>
            <h3 class="text-3xl font-black mt-1 text-center">{{ $rooms->where('status','empty')->count() }}</h3>
            <div class="w-8 h-1 bg-white/20 mx-auto mt-3 rounded-full"></div>
        </div>

        {{-- Đang thuê - Màu Indigo (Tượng trưng cho sự ổn định) --}}
        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-100 text-white transition-transform hover:scale-[1.02]">
            <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest text-center">Phòng đang thuê</p>
            <h3 class="text-3xl font-black mt-1 text-center">{{ $rooms->where('status','occupied')->count() }}</h3>
            <div class="w-8 h-1 bg-white/20 mx-auto mt-3 rounded-full"></div>
        </div>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-8 py-5">Phòng / Ảnh</th>
                        <th class="px-8 py-5">Tòa nhà</th>
                        <th class="px-8 py-5 text-center">Tầng/DT</th>
                        <th class="px-8 py-5 text-center">Giá thuê</th>
                        <th class="px-8 py-5 text-center">Người ở</th>
                        <th class="px-8 py-5 text-center">Trạng thái</th>
                        <th class="px-8 py-5 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($rooms as $room)
                    <tr class="group hover:bg-slate-50/80 transition-all search-item">
                        {{-- Room Code & Image --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="relative group/img overflow-hidden w-16 h-12 rounded-xl bg-slate-100">
                                    @if($room->images && $room->images->count())
                                        <img src="{{ asset('storage/'.$room->images[0]->image_path) }}" 
                                             onclick="showImage(this.src)"
                                             class="w-full h-full object-cover cursor-zoom-in transition-transform duration-500 group-hover/img:scale-110">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-slate-400">N/A</div>
                                    @endif
                                </div>
                                <span class="font-black text-slate-700 r-code">{{ $room->room_code }}</span>
                            </div>
                        </td>

                        {{-- Building --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <span class="text-slate-600 font-bold r-building">{{ $room->building->name ?? 'N/A' }}</span>
                            </div>
                        </td>

                        {{-- Floor & Area --}}
                        <td class="px-8 py-5 text-center">
                            <div class="inline-flex flex-col">
                                <span class="text-xs font-black text-slate-800 tracking-tighter">Tầng {{ $room->floor }}</span>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase">{{ $room->area }} m²</span>
                            </div>
                        </td>

                        {{-- Price --}}
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-black text-slate-800">
                                {{ number_format($room->price) }}<small class="ml-0.5 text-[10px] text-slate-400">đ</small>
                            </span>
                        </td>

                        {{-- SỐ NGƯỜI ĐANG Ở --}}
                        <td class="px-8 py-5 text-center">
                        @php
                            $current = $room->current_people ?? 0;
                            $max = $room->max_people ?? 1;
                        @endphp

                        <div class="inline-flex items-center justify-center px-3 py-1.5 rounded-full 
                            {{ $current >= $max ? 'bg-rose-50 text-rose-600' : 'bg-indigo-50 text-indigo-600' }}
                            text-xs font-black">
                            {{ $current }}/{{ $max }}
                        </div>
                    </td>

                        {{-- Status --}}
                        <td class="px-8 py-5 text-center">
                            @if($room->status == 'empty')
                                <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">Trống</span>
                            @elseif($room->status == 'occupied')
                                <span class="px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase">Đang thuê</span>
                            @else
                                <span class="px-3 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase">Bảo trì</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">

                                    {{-- Nút sửa --}}
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                    class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400
                                            hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">

                                        ✏️
                                    </a>


                                    {{-- Nút xoá --}}
                                    <form method="POST"
                                        action="{{ route('admin.rooms.destroy',$room->id) }}"
                                        onsubmit="return confirm('Xác nhận xóa phòng này?')"
                                        class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400
                                                hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">

                                            🗑
                                        </button>

                                    </form>

                                </div>
                            </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <p class="text-slate-400 font-bold">🚫 Chưa có dữ liệu phòng</p>
                        </td>
=======
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tổng số phòng</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $rooms->count() }}</h3>
        </div>

        <div class="bg-emerald-600 p-6 rounded-xl text-white text-center shadow-lg shadow-emerald-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Phòng trống</p>
            <h3 class="text-3xl font-black">{{ $rooms->where('status','empty')->count() }}</h3>
        </div>

        <div class="bg-indigo-600 p-6 rounded-xl text-white text-center shadow-lg shadow-indigo-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Đang thuê</p>
            <h3 class="text-3xl font-black">{{ $rooms->where('status','occupied')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full">
                <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left">Phòng</th>
                        <th class="px-6 py-4 text-left">Tòa nhà</th>
                        <th class="px-6 py-4 text-center">Tầng/DT</th>
                        <th class="px-6 py-4 text-center">Tiện nghi</th>
                        <th class="px-6 py-4 text-center">Giá thuê</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($rooms as $room)
                    @php
                        $roomImages = $room->images?->map(fn($img) => asset('storage/'.$img->image_path))->values()->toArray() ?? [];
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors search-item">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative w-10 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                                    @if(count($roomImages) > 0)
                                        <img src="{{ $roomImages[0] }}"
                                             alt="Ảnh phòng {{ $room->room_code }}"
                                             @click="openGallery({{ \Illuminate\Support\Js::from($roomImages) }}, 0)"
                                             class="w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform">
                                        @if(count($roomImages) > 1)
                                            <span class="absolute bottom-0 right-0 bg-black/60 text-white text-[8px] px-1 rounded-tl">
                                                {{ count($roomImages) }}
                                            </span>
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-400 font-bold">N/A</div>
                                    @endif
                                </div>
                                <b class="text-slate-700 r-code">{{ $room->room_code }}</b>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-slate-600 r-building">
                            {{ $room->building->name ?? 'N/A' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-bold block">Tầng {{ $room->floor }}</span>
                            <span class="text-[11px] text-indigo-500 font-bold">{{ $room->area }} m²</span>
                        </td>

                        <td class="px-6 py-4">
                            @if($room->assets && $room->assets->count())
                                <div class="flex flex-wrap gap-1.5 max-w-[260px]">
                                    @foreach($room->assets->take(4) as $asset)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-600">
                                            @switch($asset->name)
                                                @case('Điều hòa')
                                                    
                                                    @break
                                                @case('Nóng lạnh')
                                                @case('Nông lạnh')
                                                    
                                                    @break
                                                @case('Wifi')
                                                    
                                                    @break
                                                @case('Giường')
                                                    
                                                    @break
                                                @case('Tủ lạnh')
                                                    
                                                    @break
                                                @case('Máy giặt')
                                                    
                                                    @break
                                                @default
                        
                                            @endswitch
                                            <span>{{ $asset->name }}</span>
                                            @if(($asset->quantity ?? 1) > 1)
                                                <span class="text-slate-400">x{{ $asset->quantity }}</span>
                                            @endif
                                        </span>
                                    @endforeach

                                    @if($room->assets->count() > 4)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-[11px] font-bold text-indigo-600">
                                            +{{ $room->assets->count() - 4 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-[11px] font-bold text-slate-300 italic">Chưa có</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center font-black text-slate-800">
                            {{ number_format($room->price) }}đ
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $statusMap = [
                                    'empty' => ['label' => 'Trống', 'class' => 'text-emerald-600'],
                                    'occupied' => ['label' => 'Đang thuê', 'class' => 'text-indigo-600'],
                                    'maintenance' => ['label' => 'Bảo trì', 'class' => 'text-rose-600']
                                ];
                                $curr = $statusMap[$room->status] ?? ['label' => $room->status, 'class' => 'text-slate-600'];
                            @endphp
                            <span class="font-bold text-xs {{ $curr['class'] }}">{{ $curr['label'] }}</span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                   class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('admin.rooms.destroy',$room->id) }}" method="POST" onsubmit="return confirm('Xóa phòng?')">
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
                        <td colspan="6" class="py-10 text-center text-slate-400">Chưa có dữ liệu phòng</td>
>>>>>>> feb1f02 (first commit)
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
<<<<<<< HEAD
</div>

{{-- MODAL THÊM PHÒNG --}}
<div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-3xl rounded-[2.5rem] shadow-2xl animate-modal overflow-hidden">
        <div class="p-8 md:p-10">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">Cấu hình phòng mới</h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">Thông số kỹ thuật và trạng thái</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Mã số phòng</label>
                        <input name="room_code" required placeholder="VD: P.402" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Thuộc tòa nhà</label>
                        <select name="building_id" required class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                            <option value="">-- Chọn tòa nhà --</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tầng</label>
                        <input type="number" name="floor" placeholder="4" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Diện tích (m²)</label>
                        <input type="number" step="0.1" name="area" placeholder="25.5" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Số Người</label>
                        <input type="number" step="0.1" name="max_people" placeholder="2" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Giá thuê (VNĐ/tháng)</label>
                        <input type="number" name="price" required placeholder="5000000" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Trạng thái ban đầu</label>
                        <select name="status" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                            <option value="empty">Phòng trống</option>
                            <option value="occupied">Đang thuê</option>
                            <option value="maintenance">Đang bảo trì</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-3">

                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        Tiện nghi phòng
                    </label>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm font-bold text-slate-600">

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="assets[]" value="Điều hòa">
                            Điều hòa
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="assets[]" value="Nóng lạnh">
                            Nóng lạnh
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="assets[]" value="Wifi">
                            Wifi
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="assets[]" value="Giường">
                            Giường
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="assets[]" value="Tủ lạnh">
                            Tủ lạnh
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="assets[]" value="Máy giặt">
                            Máy giặt
                        </label>

                    </div>
                </div>
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Hình ảnh thực tế</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full px-5 py-2 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-3 pt-6 border-t border-slate-50">
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all">Lưu thông tin</button>
                    <button type="button" onclick="closeModal()" class="px-8 py-4 bg-transparent text-slate-400 font-bold text-sm hover:text-slate-600 transition-all">Đóng</button>
=======

    {{-- MODAL XEM ẢNH GIỐNG TRANG TENANT --}}
    <template x-teleport="body">
        <div x-show="openModal"
             x-transition.opacity
             class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-950/95 backdrop-blur-xl"
             @keydown.escape.window="closeGallery()">

            <button @click="closeGallery()"
                    class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors p-2 z-20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="relative w-full max-w-6xl px-4 flex items-center justify-center">
                <button x-show="images.length > 1"
                        @click="prevImage()"
                        class="absolute left-8 z-10 bg-white/10 hover:bg-indigo-600 text-white p-4 rounded-full transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="relative group">
                    <img :src="images[currentIndex]"
                         class="max-h-[75vh] w-auto rounded-2xl shadow-2xl border border-white/10 object-contain">

                    <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 text-white/60 font-medium">
                        <span x-text="currentIndex + 1" class="text-white font-bold"></span> /
                        <span x-text="images.length"></span>
                    </div>
                </div>

                <button x-show="images.length > 1"
                        @click="nextImage()"
                        class="absolute right-8 z-10 bg-white/10 hover:bg-indigo-600 text-white p-4 rounded-full transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="mt-16 flex gap-3 overflow-x-auto px-4 py-2 max-w-full no-scrollbar">
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img"
                         @click="currentIndex = index"
                         :class="currentIndex === index ? 'ring-2 ring-indigo-500 scale-105 opacity-100' : 'opacity-40 hover:opacity-100'"
                         class="w-20 h-14 object-cover rounded-xl cursor-pointer transition-all duration-300">
                </template>
            </div>
        </div>
    </template>
</div>

{{-- MODAL TẠO PHÒNG --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[90vh] flex flex-col shadow-2xl">

        <div class="p-6 border-b flex items-center justify-between bg-white shrink-0">
            <div>
                <h3 class="text-xl font-black text-slate-900">Cấu hình phòng mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thông tin chi tiết tài sản</p>
            </div>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                ✕
            </button>
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
                        <input
                            name="room_code"
                            value="{{ old('room_code') }}"
                            required
                            placeholder="VD: P.101"
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border {{ $errors->has('room_code') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none transition-all"
                        >
                        @error('room_code')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Thuộc tòa nhà</label>
                        <select
                            name="building_id"
                            required
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border {{ $errors->has('building_id') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none"
                        >
                            <option value="">-- Chọn tòa nhà --</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b->id }}" {{ old('building_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('building_id')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Tầng</label>
                        <input type="number" name="floor" value="{{ old('floor') }}" placeholder="1"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('floor') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                        @error('floor')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Diện tích (m²)</label>
                        <input type="number" step="0.1" name="area" value="{{ old('area') }}" placeholder="25"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('area') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                        @error('area')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Max người ở</label>
                        <input type="number" name="max_people" value="{{ old('max_people') }}" placeholder="2"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold border {{ $errors->has('max_people') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                        @error('max_people')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Giá thuê tháng (VNĐ)</label>
                    <input type="number" name="price" value="{{ old('price') }}" required placeholder="3500000"
                           class="w-full p-3 bg-slate-100 rounded-xl font-black text-indigo-700 text-lg border {{ $errors->has('price') ? 'border-rose-500' : 'border-transparent' }} focus:border-indigo-500 outline-none">
                    @error('price')
                        <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Trạng thái hiện tại</label>
                    <div class="flex gap-3">
                        @foreach(['empty' => 'Phòng trống', 'occupied' => 'Đang thuê', 'maintenance' => 'Bảo trì'] as $val => $label)
                        <label class="flex-1">
                            <input type="radio" name="status" value="{{ $val }}" class="hidden peer"
                                   {{ old('status', 'empty') == $val ? 'checked' : '' }}>
                            <div class="text-center p-2 rounded-lg border-2 border-slate-50 bg-slate-50 text-slate-400 font-bold text-xs cursor-pointer peer-checked:border-indigo-600 peer-checked:text-indigo-600 peer-checked:bg-white transition-all">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('status')
                        <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="font-black text-sm mb-3 text-slate-700">🛋 Tiện nghi có sẵn</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['Điều hòa', 'Nóng lạnh', 'Wifi', 'Giường', 'Tủ lạnh', 'Máy giặt'] as $asset)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="assets[]" value="{{ $asset }}"
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600"
                                   {{ in_array($asset, old('assets', [])) ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600">{{ $asset }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Hình ảnh (Nhiều tệp)</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="w-full text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    @error('images')
                        <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu thông tin phòng
                    </button>
                    <button type="button" onclick="closeCreateModal()" class="px-8 text-slate-400 font-bold hover:text-slate-600">
                        Hủy
                    </button>
>>>>>>> feb1f02 (first commit)
                </div>
            </form>
        </div>
    </div>
</div>

<<<<<<< HEAD
{{-- IMAGE PREVIEW (FULL SCREEN) --}}
<div id="imageModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-md hidden items-center justify-center z-[200] p-4 transition-all" onclick="this.classList.replace('flex','hidden')">
    <div class="relative max-w-5xl w-full">
        <img id="previewImage" src="" class="w-full h-auto rounded-3xl shadow-2xl animate-modal border-4 border-white/10">
        <p class="text-white text-center mt-4 font-bold text-sm">Nhấn bất kỳ đâu để đóng</p>
    </div>
</div>

<script>
    // Search Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.search-item');

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            rows.forEach(row => {
                const code = row.querySelector('.r-code').textContent.toLowerCase();
                const building = row.querySelector('.r-building').textContent.toLowerCase();
                row.style.display = (code.includes(query) || building.includes(query)) ? "" : "none";
            });
        });
    });

    // Modal Controls
    function openModal() { document.getElementById('modal').classList.replace('hidden', 'flex'); }
    function closeModal() { document.getElementById('modal').classList.replace('flex', 'hidden'); }
    
    // Image Preview
    function showImage(src) {
        const modal = document.getElementById('imageModal');
        document.getElementById('previewImage').src = src;
        modal.classList.replace('hidden', 'flex');
    }

    // Close on Outside Click
    window.onclick = function(e) {
        if (e.target.id === 'modal') closeModal();
    }
=======
<script>
    function openCreateModal(){
        const m = document.getElementById('modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal(){
        const m = document.getElementById('modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(e){
        const m = document.getElementById('modal');
        if (e.target && e.target.id === 'modal') closeCreateModal();
    });

    document.getElementById('tableSearch')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.search-item').forEach(row => {
            const code = row.querySelector('.r-code').textContent.toLowerCase();
            const bld = row.querySelector('.r-building').textContent.toLowerCase();
            row.style.display = (code.includes(query) || bld.includes(query)) ? '' : 'none';
        });
    });

    @if($errors->any())
        openCreateModal();
    @endif
>>>>>>> feb1f02 (first commit)
</script>

@endsection