<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật khách thuê | Hệ thống quản lý</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        .form-card { animation: slideUp 0.5s ease-out; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="p-6 md:p-12">

<div class="max-w-3xl mx-auto">
    
    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-10">
        <a href="{{ route('admin.tenants.index') }}" 
           class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-sm transition-all group">
            <span class="group-hover:-translate-x-1 transition-transform">←</span> 
            Quay lại danh sách
        </a>
        <h2 class="text-3xl font-black text-slate-800 mt-3 tracking-tight">Cập nhật khách thuê</h2>
        <p class="text-slate-500 text-sm mt-1 font-medium">Chỉnh sửa thông tin hồ sơ của cư dân</p>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden form-card">
        <div class="p-8 md:p-12">
            
            <form method="POST" action="{{ route('admin.tenants.update', $tenant->id) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- PHẦN 1: THÔNG TIN TÀI KHOẢN (KHÔNG ĐƯỢC SỬA) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            Họ và tên
                            <span class="text-[9px] bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full normal-case">Cố định</span>
                        </label>
                        <div class="relative group">
                            <input type="text" value="{{ $tenant->user->name }}" readonly
                                   class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl font-bold text-slate-400 cursor-not-allowed outline-none">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">🔒</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            Email tài khoản
                            <span class="text-[9px] bg-slate-100 text-slate-400 px-2 py-0.5 rounded-full normal-case">Cố định</span>
                        </label>
                        <div class="relative group">
                            <input type="text" value="{{ $tenant->user->email }}" readonly
                                   class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl font-bold text-slate-400 cursor-not-allowed outline-none">
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300">🔒</span>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-50"></div>

                {{-- PHẦN 2: THÔNG TIN LIÊN HỆ & CÁ NHÂN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ $tenant->phone }}"
                               class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nghề nghiệp</label>
                        <input type="text" name="job" value="{{ $tenant->job }}"
                               class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Ngày sinh</label>
                        <input type="date" name="birthday" value="{{ $tenant->birthday }}"
                               class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Giới tính</label>
                        <div class="relative">
                            <select name="gender" class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all appearance-none cursor-pointer">
                                <option value="male" @selected($tenant->gender=='male')>Nam</option>
                                <option value="female" @selected($tenant->gender=='female')>Nữ</option>
                                <option value="other" @selected($tenant->gender=='other')>Khác</option>
                            </select>
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">▼</span>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Quê quán</label>
                        <input type="text" name="hometown" value="{{ $tenant->hometown }}"
                               class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all">
                    </div>
                </div>

                <div class="h-px bg-slate-50"></div>

                {{-- PHẦN 3: GIẤY TỜ & TRẠNG THÁI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">CCCD Mặt trước</label>
                        <input type="file" name="cccd_front" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-slate-100 file:text-slate-500 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">CCCD Mặt sau</label>
                        <input type="file" name="cccd_back" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-slate-100 file:text-slate-500 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Trạng thái cư trú</label>
                        <div class="relative">
                            <select name="status" class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus-ring transition-all appearance-none cursor-pointer">
                                <option value="free" @selected($tenant->status=='free')>🟢 Chưa thuê</option>
                                <option value="renting" @selected($tenant->status=='renting')>🏠 Đang thuê</option>
                            </select>
                            <span class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">▼</span>
                        </div>
                    </div>
                </div>

                {{-- NÚT THAO TÁC --}}
                <div class="pt-10 flex flex-col md:flex-row items-center justify-end gap-4 border-t border-slate-50">
                    <a href="{{ route('admin.tenants.index') }}" 
                       class="w-full md:w-auto px-8 py-4 text-slate-400 font-bold hover:text-slate-600 transition-all text-center">
                        Hủy bỏ
                    </a>
                    <button type="submit" 
                            class="w-full md:w-auto px-12 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:-translate-y-1 active:scale-95 transition-all">
                        Cập nhật hồ sơ
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="mt-10 text-center">
        <p class="text-slate-400 text-[11px] font-medium uppercase tracking-[0.2em]">
            Hệ thống quản lý cư dân nội bộ
        </p>
    </div>
</div>

</body>
</html>