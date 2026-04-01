@extends('admin.layout')

@section('title','Quản lý tài khoản')
@section('page_title','Hệ thống tài khoản')

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
    
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-[1400px] mx-auto" id="searchContainer">
    
    {{-- ALERT --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm animate-fadeIn">
        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm">✓</span>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
=======

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.45s ease-out; }
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
>>>>>>> feb1f02 (first commit)
    @endif

    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Danh sách thành viên</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Quản lý và phân quyền truy cập hệ thống</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
<<<<<<< HEAD
                <input type="text" id="tableSearch" placeholder="Tìm tên hoặc email..." 
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>
            <button onclick="openModal()"
=======
                <input type="text" id="tableSearch" placeholder="Tìm tên hoặc email..."
                    class="pl-11 pr-4 py-2.5 w-64 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus-ring transition-all outline-none">
            </div>

            <button type="button" onclick="openModal()"
>>>>>>> feb1f02 (first commit)
                class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-200 transition-all active:scale-95">
                <span>➕</span> Thêm tài khoản
            </button>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-white">
        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-100 flex justify-between items-center transition-transform hover:scale-[1.02]">
            <div>
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Tổng Tenant</p>
                <h3 class="text-3xl font-black mt-1">{{ $tenants->count() }}</h3>
            </div>
            <span class="text-4xl opacity-20">👤</span>
        </div>
<<<<<<< HEAD
        
=======
>>>>>>> feb1f02 (first commit)
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-table-scroll">
            <table class="w-full text-left border-collapse" id="tenantTable">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">ID</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Thành viên</th>
<<<<<<< HEAD
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Email Liên lạc</th>
=======
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Email liên lạc</th>
>>>>>>> feb1f02 (first commit)
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Trạng thái</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tenants as $tenant)
<<<<<<< HEAD
                    <tr class="group hover:bg-slate-50/80 transition-all search-item">
                        <td class="px-8 py-5">
                            <span class="font-mono text-xs font-bold text-slate-400">#{{ $tenant->id }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-black text-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                    {{ strtoupper(substr($tenant->name,0,1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 leading-none user-name">{{ $tenant->name }}</p>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1 block">Tenant</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-medium text-slate-600 user-email">{{ $tenant->email }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Hoạt động
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                                    ✏️
                                </button>
                                <form method="POST" action="{{ route('admin.users.destroy',$tenant->id) }}" onsubmit="return confirm('Xác nhận xóa tài khoản này?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-5xl mb-4 opacity-20">📂</span>
                                <p class="text-slate-400 font-bold">Chưa có dữ liệu tài khoản</p>
                            </div>
                        </td>
                    </tr>
=======
                        @php
                            $tenantProfile = $tenant->tenant ?? null;
                            $isRenting = $tenantProfile && (
                                $tenantProfile->status === 'renting' ||
                                ($tenantProfile->contracts()->where('status', 'active')->exists())
                            );
                        @endphp

                        <tr class="group hover:bg-slate-50/80 transition-all search-item">
                            <td class="px-8 py-5">
                                <span class="font-mono text-xs font-bold text-slate-400">#{{ $tenant->id }}</span>
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-black text-sm group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                        {{ strtoupper(substr($tenant->name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 leading-none user-name">{{ $tenant->name }}</p>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1 block">
                                            {{ $tenant->role ?? 'Tenant' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <span class="text-sm font-medium text-slate-600 user-email">{{ $tenant->email }}</span>
                            </td>

                            <td class="px-8 py-5">
                                @if($isRenting)
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span> Đang thuê
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Hoạt động
                                    </span>
                                @endif
                            </td>

                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">

                                    @if(!$isRenting)
                                        <form method="POST" action="{{ route('admin.users.destroy',$tenant->id) }}" onsubmit="return confirm('Xác nhận xóa tài khoản này?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
                                                🗑
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2.5 rounded-xl bg-slate-100 text-slate-400 shadow-sm cursor-not-allowed">
                                            🔒
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4 opacity-20">📂</span>
                                    <p class="text-slate-400 font-bold">Chưa có dữ liệu tài khoản</p>
                                </div>
                            </td>
                        </tr>
>>>>>>> feb1f02 (first commit)
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL THÊM TÀI KHOẢN --}}
<<<<<<< HEAD
<div id="userModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] animate-modal overflow-hidden">
        <div class="p-8 md:p-10">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">Tạo tài khoản mới</h3>
                    <p class="text-slate-400 text-sm font-medium mt-1">Thông tin truy cập hệ thống</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Họ và tên</label>
                        <input name="name" required placeholder="Nguyen Van A" 
                            class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Email liên lạc</label>
                        <input name="email" type="email" required placeholder="admin@example.com"
                            class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Mật khẩu ban đầu</label>
                        <input name="password" type="password" required placeholder="••••••••"
                            class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Quyền hạn</label>
                        <select name="role" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-700 focus-ring transition-all outline-none appearance-none">
                            <option value="tenant">Khách thuê (Tenant)</option>
                            <option value="admin">Quản trị viên (Admin)</option>
=======
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">

        {{-- Header sticky --}}
        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-xl font-black text-slate-900">Thêm tài khoản mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Thông tin truy cập & phân quyền</p>
            </div>
            <button type="button"
                    onclick="closeModal()"
                    class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                ✕
            </button>
        </div>

        {{-- Body scroll --}}
        <div class="flex-1 overflow-y-auto modal-scroll p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Họ và tên</label>
                    <input name="name" required placeholder="VD: Nguyễn Văn A"
                           value="{{ old('name') }}"
                           class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Email liên lạc</label>
                        <input name="email" type="email" required placeholder="admin@example.com"
                               value="{{ old('email') }}"
                               class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Quyền hạn</label>
                        <select name="role"
                                class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                            <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>Khách thuê (Tenant)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
>>>>>>> feb1f02 (first commit)
                        </select>
                    </div>
                </div>

<<<<<<< HEAD
                <div class="flex flex-col gap-3 pt-6">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-indigo-100 hover:bg-slate-900 hover:shadow-slate-200 transition-all">
                        💾 Lưu tài khoản
                    </button>
                    <button type="button" onclick="closeModal()" class="w-full py-4 bg-transparent text-slate-400 font-bold text-sm hover:text-slate-600 transition-all">
                        Hủy bỏ
=======
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Mật khẩu ban đầu</label>
                    <input name="password" type="password" required placeholder="••••••••"
                           class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="font-black text-sm mb-3 text-slate-700">🔐 Ghi chú phân quyền</p>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <span class="text-indigo-600 mt-0.5">•</span>
                            <p class="text-xs font-medium text-slate-600">
                                <span class="font-bold">Tenant:</span> chỉ sử dụng các chức năng được cấp trong phạm vi tài khoản thuê.
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="text-indigo-600 mt-0.5">•</span>
                            <p class="text-xs font-medium text-slate-600">
                                <span class="font-bold">Admin:</span> có quyền quản trị và truy cập toàn bộ hệ thống.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="pt-4 flex gap-3">
                    <button type="submit"
                            class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu tài khoản
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
    // Xử lý Tìm kiếm Real-time
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.search-item');
        const tbody = document.querySelector('tbody');

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let hasResults = false;

            rows.forEach(row => {
                const name = row.querySelector('.user-name').textContent.toLowerCase();
                const email = row.querySelector('.user-email').textContent.toLowerCase();

                if (name.includes(query) || email.includes(query)) {
                    row.style.display = "";
                    hasResults = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Hiển thị thông báo nếu không có kết quả
            let noResultMsg = document.getElementById('no-search-result');
            if (!hasResults) {
                if (!noResultMsg) {
                    noResultMsg = document.createElement('tr');
                    noResultMsg.id = 'no-search-result';
                    noResultMsg.innerHTML = `
                        <td colspan="5" class="px-8 py-20 text-center animate-fadeIn">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-4">🔍</span>
                                <p class="text-slate-400 font-bold text-sm">Không tìm thấy "${query}"</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(noResultMsg);
                }
            } else if (noResultMsg) {
                noResultMsg.remove();
            }
        });
    });

    // Xử lý Modal
    function openModal() {
        const modal = document.getElementById('userModal');
        modal.classList.replace('hidden', 'flex');
    }
    function closeModal() {
        const modal = document.getElementById('userModal');
        modal.classList.replace('flex', 'hidden');
    }
    window.onclick = function(e) {
        if (e.target.id === 'userModal') closeModal();
    }
=======
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearch');
        const rows = document.querySelectorAll('.search-item');
        const tbody = document.querySelector('#tenantTable tbody');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                let hasResults = false;

                rows.forEach(row => {
                    const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                    const email = row.querySelector('.user-email')?.textContent.toLowerCase() || '';

                    if (name.includes(query) || email.includes(query)) {
                        row.style.display = "";
                        hasResults = true;
                    } else {
                        row.style.display = "none";
                    }
                });

                let noResultMsg = document.getElementById('no-search-result');
                if (!hasResults && query !== '') {
                    if (!noResultMsg) {
                        noResultMsg = document.createElement('tr');
                        noResultMsg.id = 'no-search-result';
                        noResultMsg.innerHTML = `
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-4xl mb-4">🔍</span>
                                    <p class="text-slate-400 font-bold text-sm">Không tìm thấy kết quả phù hợp</p>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(noResultMsg);
                    }
                } else if (noResultMsg) {
                    noResultMsg.remove();
                }
            });
        }
    });

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
    }

    @if($errors->any())
        openModal();
    @endif
>>>>>>> feb1f02 (first commit)
</script>

@endsection