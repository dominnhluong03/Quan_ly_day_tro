@extends('admin.layout')

<<<<<<< HEAD
@section('title','Quản lý tòa nhà')
@section('page_title','Hệ thống tòa nhà')
=======
@section('title', 'Quản lý tòa nhà')
@section('page_title', 'Hệ thống tòa nhà')
>>>>>>> feb1f02 (first commit)

@section('content')

<style>
<<<<<<< HEAD
    /* Hiệu ứng mượt cho Modal */
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.9) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal { animation: modalSpring 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

    /* Thanh cuộn mảnh cho bảng */
    .custom-table-scroll::-webkit-scrollbar { height: 5px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    /* Input focus shadow */
=======
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-modal { animation: modalSpring 0.3s ease-out; }

    .custom-table-scroll::-webkit-scrollbar { height: 5px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .modal-scroll::-webkit-scrollbar { width: 5px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

>>>>>>> feb1f02 (first commit)
    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }
<<<<<<< HEAD
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
=======

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.45s ease-out; }
</style>

<div class="max-w-[1400px] mx-auto">

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-6 px-5 py-4 rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 animate-fadeIn">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
>>>>>>> feb1f02 (first commit)
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Danh sách tòa nhà</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Quản lý bất động sản và cơ sở hạ tầng</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
<<<<<<< HEAD
                <input type="text" id="tableSearch" placeholder="Tìm tên hoặc địa chỉ..." 
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>
            <button onclick="openModal()"
                class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-200 transition-all active:scale-95">
                <span>➕</span> Thêm tòa nhà
=======
                <input type="text"
                       id="tableSearch"
                       placeholder="Tìm tên hoặc địa chỉ..."
                       class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>

            <button type="button"
                    onclick="openModal()"
                    class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm tòa nhà
>>>>>>> feb1f02 (first commit)
            </button>
        </div>
    </div>

<<<<<<< HEAD
    {{-- STATS CARDS --}}
=======
    {{-- STATS --}}
>>>>>>> feb1f02 (first commit)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-white">
        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-100 flex justify-between items-center transition-transform hover:scale-[1.02]">
            <div>
                <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest">Tổng quy mô</p>
                <h3 class="text-3xl font-black mt-1">{{ $buildings->count() }} Tòa</h3>
            </div>
            <span class="text-4xl opacity-20">🏢</span>
        </div>
    </div>

<<<<<<< HEAD
    {{-- MAIN TABLE CARD --}}
=======
    {{-- TABLE --}}
>>>>>>> feb1f02 (first commit)
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50">
<<<<<<< HEAD
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Thông tin tòa nhà</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Vị trí / Địa chỉ</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-center">Ngày khởi tạo</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($buildings as $b)
                    <tr class="group hover:bg-slate-50/80 transition-all search-item">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center font-black text-lg group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-500 transition-all duration-300 shadow-sm">
                                    {{ strtoupper(substr($b->name,0,1)) }}
=======
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">
                            Thông tin tòa nhà
                        </th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">
                            Vị trí / Địa chỉ
                        </th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-center">
                            Ngày khởi tạo
                        </th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-right">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">
                @forelse($buildings as $b)
                    @php
                        $initial = strtoupper(substr($b->name ?? '', 0, 1));
                        $addr1 = trim(($b->house_number ?? '').' '.($b->street ?? '').', '.($b->ward ?? ''));
                        $addr2 = trim(($b->district ?? '').', '.($b->city ?? ''));
                    @endphp

                    <tr class="group hover:bg-slate-50/80 transition-all building-row">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center font-black text-lg group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-500 transition-all duration-300 shadow-sm">
                                    {{ $initial }}
>>>>>>> feb1f02 (first commit)
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 leading-none b-name">{{ $b->name }}</p>
                                    <div class="flex gap-1 mt-1.5">
<<<<<<< HEAD
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[9px] font-bold uppercase tracking-tighter">Premium</span>
=======
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[9px] font-bold uppercase tracking-tighter">
                                            Premium
                                        </span>
>>>>>>> feb1f02 (first commit)
                                    </div>
                                </div>
                            </div>
                        </td>
<<<<<<< HEAD
                        <td class="px-8 py-5">
                            <div class="max-w-[300px]">
                                <p class="text-sm font-bold text-slate-600 leading-snug b-address">
                                    {{ $b->house_number }} {{ $b->street }}, {{ $b->ward }}
                                </p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $b->district }}, {{ $b->city }}</p>
                            </div>
                        </td>
=======

                        <td class="px-8 py-5">
                            <div class="max-w-[300px]">
                                <p class="text-sm font-bold text-slate-600 leading-snug b-address">{{ $addr1 }}</p>
                                <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $addr2 }}</p>
                            </div>
                        </td>

>>>>>>> feb1f02 (first commit)
                        <td class="px-8 py-5 text-center">
                            <span class="text-xs font-mono font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                {{ $b->created_at->format('d/m/Y') }}
                            </span>
                        </td>
<<<<<<< HEAD
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                                    ✏️
                                </button>
                                <form method="POST" action="{{ route('admin.buildings.destroy',$b->id) }}" onsubmit="return confirm('Xóa tòa nhà này sẽ ảnh hưởng đến dữ liệu phòng?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
=======

                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button"
                                        class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm"
                                        title="Chỉnh sửa">
                                    ✏️
                                </button>

                                <form method="POST"
                                      action="{{ route('admin.buildings.destroy', $b->id) }}"
                                      onsubmit="return confirm('Xóa tòa nhà này sẽ ảnh hưởng đến dữ liệu phòng?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm"
                                            title="Xóa">
>>>>>>> feb1f02 (first commit)
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
<<<<<<< HEAD
                    @empty
=======
                @empty
>>>>>>> feb1f02 (first commit)
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-4xl opacity-20">🏢</span>
                                </div>
                                <p class="text-slate-400 font-bold">Chưa có tòa nhà nào trong hệ thống</p>
                            </div>
                        </td>
                    </tr>
<<<<<<< HEAD
                    @endforelse
=======
                @endforelse
>>>>>>> feb1f02 (first commit)
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL THÊM TÒA NHÀ --}}
<<<<<<< HEAD
<div id="modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] animate-modal overflow-hidden">
        <div class="p-8 md:p-10">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">Thêm tòa nhà mới</h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">Vui lòng nhập thông tin vị trí chính xác</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.buildings.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tên --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tên định danh tòa nhà</label>
                        <input name="name" required placeholder="VD: Vinhomes Central Park" 
                            class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    
                    {{-- Địa chỉ --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Số nhà</label>
                        <input name="house_number" placeholder="208" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Đường</label>
                        <input name="street" placeholder="Nguyễn Hữu Cảnh" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Phường/Xã</label>
                        <input name="ward" placeholder="Phường 22" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Quận/Huyện</label>
                        <input name="district" placeholder="Bình Thạnh" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Thành phố</label>
                        <input name="city" placeholder="TP. Hồ Chí Minh" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>

                    {{-- Tiện ích --}}
                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Dịch vụ & Tiện ích kèm theo</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['Wifi','Giữ xe','Camera','Thang máy'] as $a)
                            <label class="flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-2xl cursor-pointer hover:bg-indigo-50 transition-all group">
                                <input type="checkbox" name="amenities[]" value="{{ $a }}" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600">{{ $a }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-6">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-indigo-100 hover:bg-slate-900 hover:shadow-slate-200 transition-all">
                        💾 Xác nhận thêm tòa nhà
                    </button>
                    <button type="button" onclick="closeModal()" class="w-full py-4 bg-transparent text-slate-400 font-bold text-sm hover:text-slate-600 transition-all">
                        Quay lại
=======
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">

        {{-- Header --}}
        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Thêm tòa nhà mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thông tin vị trí & tiện ích</p>
            </div>
            <button type="button"
                    onclick="closeModal()"
                    class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                ✕
            </button>
        </div>

        {{-- Body scroll --}}
        <div class="p-6 overflow-y-auto modal-scroll">
            <form method="POST" action="{{ route('admin.buildings.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tên định danh tòa nhà</label>
                    <input name="name" required placeholder="VD: The Nine / Vinhomes Central Park"
                           class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Số nhà</label>
                        <input name="house_number" placeholder="28B"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Đường</label>
                        <input name="street" placeholder="Phạm Văn Đồng"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Phường/Xã</label>
                        <input name="ward" placeholder="Mai Dịch"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Quận/Huyện</label>
                        <input name="district" placeholder="Cầu Giấy"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Thành phố</label>
                    <input name="city" placeholder="Hà Nội"
                           class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="font-black text-sm mb-3 text-slate-700">🏷 Tiện ích / Dịch vụ kèm theo</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['Wifi','Giữ xe','Camera','Thang máy'] as $a)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="{{ $a }}"
                                       class="w-4 h-4 rounded border-slate-300 text-indigo-600">
                                <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600">{{ $a }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                    <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu tòa nhà
                    </button>
                    <button type="button"
                            onclick="closeModal()"
                            class="px-8 text-slate-400 font-bold hover:text-slate-600">
                        Hủy
>>>>>>> feb1f02 (first commit)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
<<<<<<< HEAD
    // Xử lý Tìm kiếm tức thì
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.search-item');

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            rows.forEach(row => {
                const name = row.querySelector('.b-name').textContent.toLowerCase();
                const address = row.querySelector('.b-address').textContent.toLowerCase();
                row.style.display = (name.includes(query) || address.includes(query)) ? "" : "none";
            });
        });
    });

    // Xử lý Modal
    function openModal() {
        const modal = document.getElementById('modal');
        modal.classList.replace('hidden', 'flex');
    }
    function closeModal() {
        const modal = document.getElementById('modal');
        modal.classList.replace('flex', 'hidden');
    }
    window.onclick = function(e) {
        if (e.target.id === 'modal') closeModal();
    }
</script>

@endsection
=======
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

    document.getElementById('tableSearch')?.addEventListener('input', function(e) {
        const q = (e.target.value || '').toLowerCase().trim();
        document.querySelectorAll('.building-row').forEach(row => {
            const name = (row.querySelector('.b-name')?.textContent || '').toLowerCase();
            const addr = (row.querySelector('.b-address')?.textContent || '').toLowerCase();
            row.style.display = (name.includes(q) || addr.includes(q)) ? '' : 'none';
        });
    });
</script>

@endsection
>>>>>>> feb1f02 (first commit)
