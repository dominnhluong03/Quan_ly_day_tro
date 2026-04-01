@extends('tenant.layout')

<<<<<<< HEAD
@section('title','Trang chủ')
@section('page_title','Thông tin phòng')
=======
@section('title', 'Trang chủ')
@section('page_title', 'Thông tin phòng')
>>>>>>> feb1f02 (first commit)

@section('content')

@if($tenant && $tenant->contracts->count())
    @php
        $contract = $tenant->contracts->first();
        $room = $contract->room;
<<<<<<< HEAD
    @endphp

    <div class="h-full w-full overflow-y-auto bg-[#f8fafc] custom-scrollbar">
        
        <div class="sticky top-0 z-20 bg-white/60 backdrop-blur-xl border-b border-slate-100 px-8 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black text-slate-800">Căn hộ {{ $room->room_code }}</h1>
                <p class="text-xs text-slate-500 font-medium">{{ $room->building->name }} • Tầng {{ $room->floor }}</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-wider">Đang thuê</span>
            </div>
        </div>

        <div class="p-6 md:p-8 max-w-7xl mx-auto space-y-8 animate-slide-up">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                <div class="md:col-span-7 group relative h-[400px] overflow-hidden rounded-[2.5rem] shadow-2xl shadow-indigo-100/50">
                    @if($room->images->count())
                        <img src="{{ asset('storage/'.$room->images->first()->image_path) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                            <span class="text-6xl">🏠</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                </div>

                <div class="md:col-span-5 grid grid-cols-2 gap-4">
                    <div class="col-span-2 bg-indigo-600 rounded-[2rem] p-6 text-white flex flex-col justify-between shadow-lg shadow-indigo-200">
                        <div class="flex justify-between items-start">
                            <span class="p-2 bg-white/20 rounded-xl">💳</span>
                            <span class="text-[10px] font-bold opacity-60 uppercase tracking-[0.2em]">Monthly Rent</span>
                        </div>
                        <div>
                            <p class="text-3xl font-black">{{ number_format($room->price) }} <span class="text-sm font-light">VNĐ</span></p>
                            <p class="text-xs text-indigo-200 mt-1 italic">* Chưa bao gồm phí dịch vụ</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center hover:shadow-md transition-all">
                        <span class="text-2xl font-black text-indigo-600">{{ $room->area }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Diện tích m²</span>
                    </div>

                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center hover:shadow-md transition-all">
                        <span class="text-2xl font-black text-slate-800">{{ $room->max_people }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Người tối đa</span>
                    </div>
                </div>

                <div class="md:col-span-4 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full italic font-serif leading-none"></span> Hợp đồng của bạn
                    </h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Bắt đầu</p>
                                <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Kết thúc</p>
                                <p class="font-bold text-rose-500">{{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</p>
=======

        $start = \Carbon\Carbon::parse($contract->start_date);
        $end = \Carbon\Carbon::parse($contract->end_date);
        $now = \Carbon\Carbon::now();
        $total = max(1, $start->diffInDays($end));
        $remaining = $now->diffInDays($end, false);
        $percent = max(0, min(100, ($remaining / $total) * 100));
    @endphp

    <div class="min-h-screen bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 space-y-5">

            {{-- Header gọn --}}
            <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-2xl font-black text-slate-900">Phòng {{ $room->room_code }}</h1>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Đang thuê
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $room->building->name }} • Tầng {{ $room->floor }}
                        </p>
                    </div>

                    <div class="text-left md:text-right">
                        <p class="text-xs uppercase tracking-wider font-black text-slate-400">Giá thuê</p>
                        <p class="text-xl font-black text-slate-900">{{ number_format($room->price) }} đ/tháng</p>
                    </div>
                </div>
            </div>

            {{-- Khối chính gọn hơn --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Ảnh nhỏ hơn + thông tin cơ bản --}}
                <div class="space-y-5">
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="h-56 bg-slate-100">
                            @if($room->images->count())
                                <img src="{{ asset('storage/'.$room->images->first()->image_path) }}"
                                     alt="Ảnh phòng {{ $room->room_code }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-5xl mb-2">🏠</div>
                                        <p class="text-slate-400 text-sm font-semibold">Chưa có ảnh phòng</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Diện tích</span>
                                    <span class="font-bold text-slate-800">{{ $room->area }} m²</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Sức chứa</span>
                                    <span class="font-bold text-slate-800">{{ $room->max_people }} người</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Đang ở</span>
                                    <span class="font-bold text-slate-800">{{ $room->contracts->count() }}/{{ $room->max_people }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hợp đồng --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-base font-black text-slate-900 mb-4">Hợp đồng thuê</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                    <p class="text-[11px] uppercase font-black tracking-wider text-slate-400">Bắt đầu</p>
                                    <p class="text-sm font-bold text-slate-800 mt-1">
                                        {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                    <p class="text-[11px] uppercase font-black tracking-wider text-slate-400">Kết thúc</p>
                                    <p class="text-sm font-bold text-rose-600 mt-1">
                                        {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-slate-500">Còn lại</span>
                                    <span class="text-sm font-black text-indigo-600">{{ max(0, floor($remaining)) }} ngày</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-200 overflow-hidden">
                                    <div class="h-full rounded-full bg-slate-900" style="width: {{ $percent }}%"></div>
                                </div>
>>>>>>> feb1f02 (first commit)
                            </div>
                        </div>
                    </div>
                </div>

<<<<<<< HEAD
                <div class="md:col-span-8 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6">Thành viên trong phòng</h3>
                    <div class="flex flex-wrap gap-6">
                        @foreach($room->contracts as $ct)
                            <div class="flex items-center gap-3 p-2 pr-6 rounded-full bg-slate-50 border border-slate-100 group hover:bg-white hover:shadow-lg transition-all">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg border-2 border-white shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    {{ strtoupper(substr($ct->tenant->user->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 leading-none">{{ $ct->tenant->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $ct->tenant->id == $tenant->id ? 'Bạn' : 'Thành viên' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="h-20"></div>
=======
                {{-- Cột phải --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Thành viên --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-black text-slate-900">Thành viên trong phòng</h3>
                            <span class="text-sm font-bold text-slate-500">{{ $room->contracts->count() }} người</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($room->contracts as $ct)
                                <div class="rounded-xl border {{ $ct->tenant->id == $tenant->id ? 'border-indigo-200 bg-indigo-50/50' : 'border-slate-200 bg-slate-50' }} p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black">
                                            {{ strtoupper(substr($ct->tenant->user->name, 0, 1)) }}
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="font-bold text-slate-800 truncate">{{ $ct->tenant->user->name }}</p>
                                                @if($ct->tenant->id == $tenant->id)
                                                    <span class="px-2 py-0.5 rounded-full bg-indigo-600 text-white text-[10px] font-black uppercase">
                                                        Bạn
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-slate-500 mt-1">
                                                {{ $ct->tenant->phone ?? 'Chưa cập nhật số điện thoại' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tiện ích --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-black text-slate-900">Tiện ích & nội thất</h3>
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                {{ $room->assets->count() }} món
                            </span>
                        </div>

                        @if($room->assets && $room->assets->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($room->assets as $asset)
                                    <div class="flex items-center justify-between gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-lg">
                                                @switch($asset->name)
                                                    @case('Điều hòa') ❄️ @break
                                                    @case('Nông lạnh')
                                                    @case('Nóng lạnh') 🚿 @break
                                                    @case('Wifi') 📶 @break
                                                    @case('Tủ lạnh') 🧊 @break
                                                    @case('Máy giặt') 🧺 @break
                                                    @default 🏷️
                                                @endswitch
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <p class="font-bold text-slate-800">{{ $asset->name }}</p>
                                                    @if($asset->quantity > 1)
                                                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">
                                                            x{{ $asset->quantity }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($asset->note)
                                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $asset->note }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <span class="px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap
                                            {{ $asset->status === 'good'
                                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
                                                : ($asset->status === 'maintenance'
                                                    ? 'bg-amber-50 text-amber-700 border border-amber-100'
                                                    : 'bg-rose-50 text-rose-700 border border-rose-100') }}">
                                            {{ $asset->status === 'good' ? 'Tốt' : ($asset->status === 'maintenance' ? 'Bảo trì' : 'Hỏng') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">🏷️</div>
                                <p class="font-bold text-slate-700">Chưa có tiện ích nào</p>
                            </div>
                        @endif
                    </div>

                    {{-- Nút hành động gọn --}}
                </div>
            </div>
>>>>>>> feb1f02 (first commit)
        </div>
    </div>

@else
<<<<<<< HEAD
    @endif

<style>
    /* Thanh cuộn siêu đẹp dành cho nội dung */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
=======
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm max-w-md w-full">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">Không tìm thấy thông tin</h3>
            <p class="text-slate-500">Bạn chưa có hợp đồng thuê phòng nào.</p>
        </div>
    </div>
@endif

<style>
    html { scroll-behavior: smooth; }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
>>>>>>> feb1f02 (first commit)
    }
</style>

@endsection