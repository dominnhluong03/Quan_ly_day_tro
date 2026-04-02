@extends('admin.layout')

@section('title', 'Quản lý khách thuê')
@section('page_title', 'Hệ thống khách thuê')

@section('content')
<style>
    @keyframes modalSpring {
        0% { opacity: 0; transform: scale(0.95) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    .animate-modal { animation: modalSpring 0.3s ease-out; }

    .modal-scroll::-webkit-scrollbar { width: 5px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .cccd-thumb { transition: all .25s ease; }
    .cccd-thumb:hover { transform: scale(1.5) translateY(-5px); z-index: 10; }

    .focus-ring:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn { animation: fadeIn 0.45s ease-out; }
</style>

<div class="max-w-[1400px] mx-auto pb-10">
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

    @if($errors->any())
        <div class="mb-6 px-5 py-4 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 animate-fadeIn">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Quản lý khách thuê</h2>
            <p class="text-slate-500 text-sm">Dữ liệu cư dân và thông tin định danh</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative hidden lg:block">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" id="tableSearch" placeholder="Tìm tên hoặc số điện thoại..."
                       class="pl-11 pr-4 py-2.5 w-72 bg-white border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
            </div>

            <button type="button" onclick="openModal()"
                    class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                ➕ Thêm khách thuê
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tổng khách hàng</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $tenants->count() }}</h3>
        </div>

        <div class="bg-indigo-600 p-6 rounded-xl text-white text-center shadow-lg shadow-indigo-100">
            <p class="text-xs font-bold uppercase tracking-wider opacity-80">Đang cư trú</p>
            <h3 class="text-3xl font-black">{{ $tenants->where('status', 'renting')->count() }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <table class="w-full">
            <thead class="bg-slate-100 text-xs uppercase text-slate-500">
            <tr>
                <th class="px-6 py-4 text-left">Thông tin khách</th>
                <th class="px-6 py-4 text-left">Liên hệ</th>
                <th class="px-6 py-4 text-left">Định danh (CCCD)</th>
                <th class="px-6 py-4 text-left">Quê quán</th>
                <th class="px-6 py-4 text-center">Trạng thái</th>
                <th class="px-6 py-4 text-right">Thao tác</th>
            </tr>
            </thead>

            <tbody class="divide-y">
            @forelse($tenants as $t)
                @php
                    $name = $t->user->name ?? 'Không xác định';
                    $email = $t->user->email ?? '';
                    $genderLabel = $t->gender === 'male' ? 'Nam' : ($t->gender === 'female' ? 'Nữ' : 'Khác');
                    $genderClass = $t->gender === 'male' ? 'text-blue-500' : ($t->gender === 'female' ? 'text-pink-500' : 'text-slate-400');
                @endphp

                <tr class="hover:bg-slate-50 transition-colors search-item">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-black text-slate-500">
                                {{ strtoupper(substr($name, 0, 1)) }}
                            </div>
                            <div>
                                <b class="text-slate-700 tenant-name">{{ $name }}</b>
                                <div class="mt-1">
                                    <span class="text-[10px] font-bold uppercase tracking-tighter {{ $genderClass }}">{{ $genderLabel }}</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-700 tenant-phone">{{ $t->phone }}</span>
                            <span class="text-[11px] text-slate-400 font-medium">{{ $email }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-2 items-center">
                            @if($t->cccd_front)
                                <img src="{{ asset('storage/'.$t->cccd_front) }}" onclick="showImage(this.src)"
                                     class="w-10 h-7 rounded border border-slate-200 object-cover cursor-zoom-in cccd-thumb shadow-sm" alt="CCCD Front">
                            @endif

                            @if($t->cccd_back)
                                <img src="{{ asset('storage/'.$t->cccd_back) }}" onclick="showImage(this.src)"
                                     class="w-10 h-7 rounded border border-slate-200 object-cover cursor-zoom-in cccd-thumb shadow-sm" alt="CCCD Back">
                            @endif

                            @if(!$t->cccd_front && !$t->cccd_back)
                                <span class="text-[10px] font-bold text-slate-300 italic uppercase">Chưa cập nhật</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $t->hometown ?? '---' }}</td>

                    <td class="px-6 py-4 text-center">
                        @if($t->status === 'renting')
                            <span class="font-bold text-xs text-emerald-600">Đang thuê</span>
                        @else
                            <span class="font-bold text-xs text-slate-500">Chưa thuê</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.tenants.edit', $t->id) }}"
                               class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition">✏️ Sửa</a>

                            @if($t->status !== 'renting')
                                <form action="{{ route('admin.tenants.destroy', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách thuê này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 font-bold text-sm hover:bg-rose-100 transition">🗑 Xóa</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-slate-400">Danh sách khách thuê đang trống</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[300] p-4">
    <div class="bg-white w-full max-w-2xl rounded-xl animate-modal overflow-hidden max-h-[95vh] flex flex-col shadow-2xl">
        <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0">
            <div>
                <h3 class="text-xl font-black text-slate-900">Cấu hình khách thuê mới</h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Liên kết tài khoản & thông tin định danh</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">✕</button>
        </div>

        <div class="p-6 overflow-y-auto modal-scroll">
            <form method="POST" action="{{ route('admin.tenants.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Chọn tài khoản hệ thống</label>
                    <select name="user_id" required class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                        <option value="">-- Chọn tài khoản người dùng --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Số điện thoại</label>
                        <input type="text" name="phone" required placeholder="090..." class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Giới tính</label>
                        <select name="gender" class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày sinh</label>
                        <input type="date" name="birthday" class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Nghề nghiệp</label>
                        <input type="text" name="job" placeholder="VD: Kỹ sư, Sinh viên..." class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1 block">Quê quán</label>
                    <input type="text" name="hometown" placeholder="VD: Quận 1, TP. Hồ Chí Minh" class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Mặt trước CCCD</label>
                        <input type="file" name="cccd_front" accept="image/*" class="w-full text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1 block">Mặt sau CCCD</label>
                        <input type="file" name="cccd_back" accept="image/*" class="w-full text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                    <button type="submit" class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Lưu thông tin khách
                    </button>
                    <button type="button" onclick="closeModal()" class="px-8 text-slate-400 font-bold hover:text-slate-600">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[400] p-4" onclick="hideImage()">
    <div class="relative max-w-4xl w-full flex justify-center">
        <img id="previewImage" src="" class="max-w-full max-h-[90vh] rounded-xl shadow-2xl animate-modal border border-white/10" alt="Preview">
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
        const query = (e.target.value || '').toLowerCase().trim();
        document.querySelectorAll('.search-item').forEach(row => {
            const name = row.querySelector('.tenant-name')?.textContent.toLowerCase() || '';
            const phone = row.querySelector('.tenant-phone')?.textContent.toLowerCase() || '';
            row.style.display = (name.includes(query) || phone.includes(query)) ? '' : 'none';
        });
    });

    function showImage(src) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('previewImage');
        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function hideImage() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    @if($errors->any())
        openModal();
    @endif
</script>
@endsection