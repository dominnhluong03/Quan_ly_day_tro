@extends('tenant.layout')

@section('title','Danh sách phòng trọ')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-4 py-8"
     x-data="{
        openModal: false,
        images: [],
        currentIndex: 0,
        searchQuery: '',
        statusFilter: 'all',

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
        nextImage() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
        prevImage() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
     }">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Hệ thống phòng</h2>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                @if($building) 
                    <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                    Tòa nhà: <span class="font-bold text-slate-700">{{ $building->name }}</span> 
                @endif
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input x-model="searchQuery" type="text" placeholder="Tìm mã phòng..."
                       class="pl-10 pr-4 py-3 w-full sm:w-64 bg-white border border-slate-200 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm">
            </div>

            <select x-model="statusFilter"
                    class="px-4 py-3 bg-white border border-slate-200 rounded-2xl shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none text-sm font-semibold text-slate-600 cursor-pointer">
                <option value="all">Tất cả trạng thái</option>
                <option value="empty">Còn trống</option>
                <option value="occupied">Đang thuê</option>
                <option value="maintenance">Bảo trì</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($rooms as $room)
        @php
            $roomImages = $room->images->map(fn($img) => asset('storage/'.$img->image_path))->toArray();
        @endphp

        <div x-show="(searchQuery === '' || '{{ $room->room_code }}'.toLowerCase().includes(searchQuery.toLowerCase())) && (statusFilter === 'all' || statusFilter === '{{ $room->status }}')"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="group bg-white rounded-[28px] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 overflow-hidden flex flex-col">

            <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                @if(count($roomImages) > 0)
                    <img src="{{ $roomImages[0] }}"
                         alt="Phòng {{ $room->room_code }}"
                         class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
                        <button @click="openGallery({{ json_encode($roomImages) }}, 0)"
                                class="bg-white/90 backdrop-blur text-slate-900 px-5 py-2.5 rounded-full text-sm font-bold shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-white">
                            Xem ảnh chi tiết
                        </button>
                    </div>

                    <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-md text-white px-2.5 py-1 rounded-lg text-[10px] font-bold">
                        {{ count($roomImages) }} ảnh
                    </div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Không có ảnh</span>
                    </div>
                @endif

                <div class="absolute top-4 left-4">
                    @php
                        $statusClasses = [
                            'empty' => 'bg-emerald-500 shadow-emerald-500/40',
                            'occupied' => 'bg-amber-500 shadow-amber-500/40',
                            'maintenance' => 'bg-rose-500 shadow-rose-500/40'
                        ];
                        $statusLabels = [
                            'empty' => 'Còn trống',
                            'occupied' => 'Đang thuê',
                            'maintenance' => 'Bảo trì'
                        ];
                    @endphp
                    <span class="{{ $statusClasses[$room->status] ?? 'bg-slate-500' }} text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg uppercase tracking-[0.12em]">
                        {{ $statusLabels[$room->status] ?? $room->status }}
                    </span>
                </div>
            </div>

            <div class="p-5 flex flex-col flex-grow">
                <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="min-w-0">
                        <h3 class="text-lg font-black text-slate-800 tracking-tight leading-none group-hover:text-indigo-600 transition-colors">
                            Phòng {{ $room->room_code }}
                        </h3>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-600">
                                Tầng {{ $room->floor ?? '--' }}
                            </span>

                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-600">
                                {{ $room->area ? rtrim(rtrim(number_format($room->area, 1), '0'), '.') : '--' }} m²
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0 text-right">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">
                            Giá thuê
                        </p>
                        <p class="mt-1 text-indigo-600 font-black text-xl leading-none">
                            {{ number_format($room->price) }}
                        </p>
                        <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-indigo-400">
                            đ / tháng
                        </p>
                    </div>
                </div>

                <div class="mt-auto pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tiện nghi có sẵn</p>
                    </div>

                    @if($room->assets && $room->assets->count() > 0)
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($room->assets->take(6) as $asset)
                                <div class="flex items-center bg-slate-50 hover:bg-indigo-600 border border-slate-200/60 hover:border-indigo-600 px-2.5 py-1 rounded-lg transition-all duration-200 cursor-default group/tag">
                                    <span class="text-[11px] font-bold text-slate-600 group-hover/tag:text-white transition-colors">
                                        {{ $asset->name }}
                                    </span>

                                    @if(($asset->quantity ?? 1) > 1)
                                        <span class="ml-1 text-[9px] font-black text-indigo-500 group-hover/tag:text-indigo-200">
                                            {{ $asset->quantity }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach

                            @if($room->assets->count() > 6)
                                <div class="flex items-center bg-white border border-dashed border-slate-300 px-2.5 py-1 rounded-lg">
                                    <span class="text-[10px] font-black text-slate-400 italic">
                                        +{{ $room->assets->count() - 6 }} nữa
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="py-3 flex items-center justify-center bg-slate-50/60 border border-dashed border-slate-200 rounded-xl">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter italic">
                                Cơ bản (Chưa có nội thất)
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <template x-teleport="body">
        <div x-show="openModal"
             class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-950/95 backdrop-blur-xl"
             @keydown.escape.window="closeGallery()">

            <button @click="closeGallery()" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="relative w-full max-w-6xl px-4 flex items-center justify-center">
                <button x-show="images.length > 1" @click="prevImage()" class="absolute left-8 z-10 bg-white/10 hover:bg-indigo-600 text-white p-4 rounded-full transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="relative group">
                    <img :src="images[currentIndex]"
                         class="max-h-[75vh] w-auto rounded-2xl shadow-2xl border border-white/10 object-contain">

                    <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 text-white/60 font-medium">
                        <span x-text="currentIndex + 1" class="text-white font-bold"></span> / <span x-text="images.length"></span>
                    </div>
                </div>

                <button x-show="images.length > 1" @click="nextImage()" class="absolute right-8 z-10 bg-white/10 hover:bg-indigo-600 text-white p-4 rounded-full transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="mt-16 flex gap-3 overflow-x-auto px-4 py-2 max-w-full no-scrollbar">
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img" @click="currentIndex = index"
                         :class="currentIndex === index ? 'ring-2 ring-indigo-500 scale-105 opacity-100' : 'opacity-40 hover:opacity-100'"
                         class="w-20 h-14 object-cover rounded-xl cursor-pointer transition-all duration-300">
                </template>
            </div>
        </div>
    </template>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection