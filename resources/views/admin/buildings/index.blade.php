@extends('admin.layout')

@section('title', 'Quản lý tòa nhà')
@section('page_title', 'Hệ thống tòa nhà')

@section('content')
<style>
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.96) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    .animate-modal { animation: modalSpring .25s ease-out; }

    .custom-table-scroll::-webkit-scrollbar { height: 6px; }
    .custom-table-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 999px; }

    .modal-scroll::-webkit-scrollbar { width: 6px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }

    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .10);
        border-color: #6366f1;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn { animation: fadeIn .35s ease-out; }
</style>

<div class="max-w-[1400px] mx-auto" id="searchContainer">
    {{-- ALERT --}}
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

    @if($errors->any())
        <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-700 shadow-sm animate-fadeIn">
            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-sm">✕</span>
            <p class="font-bold text-sm">{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Danh sách tòa nhà</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Quản lý bất động sản và cơ sở hạ tầng</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" id="tableSearch" placeholder="Tìm tên hoặc địa chỉ..."
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>

            <button type="button" onclick="openModal()"
                class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm tòa nhà
            </button>
        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-white">
        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-100 flex justify-between items-center transition-transform hover:scale-[1.02]">
            <div>
                <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest">Tổng quy mô</p>
                <h3 class="text-3xl font-black mt-1">{{ $buildings->count() }} Tòa</h3>
            </div>
            <span class="text-4xl opacity-20">🏢</span>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left border-collapse" id="buildingTable">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Thông tin tòa nhà</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Vị trí / Địa chỉ</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-center">Ngày khởi tạo</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-right">Thao tác</th>
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
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 leading-none b-name">{{ $b->name }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[9px] font-bold uppercase tracking-tighter">Tòa nhà</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div class="max-w-[320px]">
                                    <p class="text-sm font-bold text-slate-600 leading-snug b-address">{{ $addr1 }}</p>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $addr2 }}</p>
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <span class="text-xs font-mono font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                    {{ optional($b->created_at)->format('d/m/Y') }}
                                </span>
                            </td>

                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form method="POST" action="{{ route('admin.buildings.destroy', $b->id) }}" class="inline"
                                        onsubmit="return confirm('Xóa tòa nhà này sẽ ảnh hưởng đến dữ liệu phòng?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm" title="Xóa">
                                            🗑
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
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
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL THÊM TÒA NHÀ --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">
        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Thêm tòa nhà mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thông tin vị trí & tiện ích</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">✕</button>
        </div>

        <div class="p-6 overflow-y-auto modal-scroll">
            <form method="POST" action="{{ route('admin.buildings.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Tên định danh tòa nhà</label>
                    <input name="name" required placeholder="VD: The Nine / Vinhomes Central Park" value="{{ old('name') }}"
                        class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Số nhà</label>
                        <input name="house_number" placeholder="28B" value="{{ old('house_number') }}"
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Đường</label>
                        <input name="street" placeholder="Phạm Văn Đồng" value="{{ old('street') }}"
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Phường/Xã</label>
                        <input name="ward" placeholder="Mai Dịch" value="{{ old('ward') }}"
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Quận/Huyện</label>
                        <input name="district" placeholder="Cầu Giấy" value="{{ old('district') }}"
                            class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Thành phố</label>
                    <input name="city" placeholder="Hà Nội" value="{{ old('city') }}"
                        class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="font-black text-sm mb-3 text-slate-700">🏷 Tiện ích / Dịch vụ kèm theo</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['Wifi','Giữ xe','Camera','Thang máy'] as $a)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="{{ $a }}"
                                    {{ is_array(old('amenities')) && in_array($a, old('amenities')) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600">
                                <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600">{{ $a }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                    <button type="submit" class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu tòa nhà
                    </button>
                    <button type="button" onclick="closeModal()" class="px-8 text-slate-400 font-bold hover:text-slate-600">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() {
        const m = document.getElementById('modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const m = document.getElementById('modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(e) {
        if (e.target && e.target.id === 'modal') closeModal();
    };

    document.getElementById('tableSearch')?.addEventListener('input', function(e) {
        const q = (e.target.value || '').toLowerCase().trim();
        document.querySelectorAll('.building-row').forEach(row => {
            const name = (row.querySelector('.b-name')?.textContent || '').toLowerCase();
            const addr = (row.querySelector('.b-address')?.textContent || '').toLowerCase();
            row.style.display = (name.includes(q) || addr.includes(q)) ? '' : 'none';
        });
    });

    @if($errors->any())
        openModal();
    @endif
</script>
@endsection