@extends('tenant.layout')

@section('title','Hồ sơ cá nhân')
@section('page_title','Thông tin cá nhân')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<<<<<<< HEAD
<div class="max-w-5xl mx-auto animate-fadeIn" x-data="{ openModal: false, modalImg: '' }">

    <div class="relative bg-gradient-to-br from-slate-900 to-indigo-900 rounded-[2.5rem] p-1 shadow-2xl mb-8">
        <div class="bg-white/5 backdrop-blur-md rounded-[2.4rem] p-8 md:p-10 flex flex-col md:flex-row items-center gap-8">
            <div class="relative">
                <div class="w-28 h-28 rounded-3xl bg-gradient-to-tr from-blue-400 to-indigo-500 flex items-center justify-center text-white text-4xl font-black shadow-lg transform -rotate-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="absolute -bottom-2 -right-2 bg-emerald-500 w-6 h-6 rounded-full border-4 border-slate-900"></div>
            </div>

            <div class="text-center md:text-left flex-1">
                <h1 class="text-3xl font-black text-white tracking-tight">{{ $user->name }}</h1>
            </div>

            <div class="flex flex-col gap-2 w-full md:w-auto">
                <button class="bg-white text-slate-900 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-50 transition-all active:scale-95 shadow-xl">
                    Chỉnh sửa hồ sơ
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <div class="md:col-span-7 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 h-full">
                <div class="flex items-center gap-2 mb-8 text-slate-400">
                    <span class="text-sm font-black uppercase tracking-[0.2em]">Thông tin chi tiết</span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase">Liên hệ</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-2">
                            <span class="text-indigo-500">📞</span> {{ $tenant->phone ?? '---' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase">Hộp thư</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-2">
                            <span class="text-indigo-500">✉️</span> {{ $user->email }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase">Ngày sinh</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-2">
                            <span class="text-indigo-500">📅</span> {{ $tenant->birthday ?? '---' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase">Giới tính</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-2">
                            <span class="text-indigo-500">👤</span> 
                            {{ $tenant->gender == 'male' ? 'Nam' : ($tenant->gender == 'female' ? 'Nữ' : 'Khác') }}
                        </p>
                    </div>
                    <div class="sm:col-span-2 space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase">Địa chỉ thường trú</label>
                        <p class="text-base font-bold text-slate-700 leading-relaxed italic">
                            {{ $tenant->hometown ?? 'Thông tin chưa được cập nhật...' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-5 space-y-6">
            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-200 shadow-inner overflow-hidden relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Định danh CCCD</h3>
                    <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded-lg italic">Verified</span>
                </div>

                <div class="flex flex-col gap-6">
                    <div @click="openModal = true; modalImg = '{{ asset('storage/'.$tenant->cccd_front) }}'" 
                         class="flex items-center gap-4 bg-white p-3 rounded-3xl border border-slate-200 shadow-sm group cursor-pointer hover:border-indigo-300 transition-all hover:shadow-md">
                        <div class="w-20 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                            @if($tenant && $tenant->cccd_front)
                                <img src="{{ asset('storage/'.$tenant->cccd_front) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">N/A</div>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Mặt trước</p>
                            <p class="text-xs font-bold text-indigo-600">Nhấp để xem</p>
                        </div>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-indigo-500 text-lg">🔍</span>
                        </div>
                    </div>

                    <div @click="openModal = true; modalImg = '{{ asset('storage/'.$tenant->cccd_back) }}'" 
                         class="flex items-center gap-4 bg-white p-3 rounded-3xl border border-slate-200 shadow-sm group cursor-pointer hover:border-indigo-300 transition-all hover:shadow-md">
                        <div class="w-20 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                            @if($tenant && $tenant->cccd_back)
                                <img src="{{ asset('storage/'.$tenant->cccd_back) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">N/A</div>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Mặt sau</p>
                            <p class="text-xs font-bold text-indigo-600">Nhấp để xem</p>
                        </div>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-indigo-500 text-lg">🔍</span>
                        </div>
=======
<div class="max-w-6xl mx-auto px-4 py-8 animate-fadeIn" 
     x-data="{ openModal: false, modalImg: '', openEdit: false }">

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm">
            <span class="text-lg">✨</span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Cột trái: Profile tóm tắt --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-blue-500 opacity-5"></div>
                
                <div class="relative inline-block mt-4">
                    <div class="w-28 h-28 rounded-3xl shadow-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white text-4xl font-black mx-auto mb-4 transform hover:rotate-3 transition-transform">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="absolute bottom-6 right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full"></div>
                </div>
                
                <h1 class="text-2xl font-bold text-slate-800">{{ $user->name }}</h1>
                <p class="text-slate-500 text-sm mb-8">{{ $user->email }}</p>
                
                <button @click="openEdit = true"
                    class="w-full py-4 px-6 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-indigo-100">
                    Chỉnh sửa hồ sơ
                </button>
            </div>

            {{-- Thẻ CCCD --}}
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Định danh cá nhân</h3>
                <div class="space-y-3">
                    {{-- Mặt trước --}}
                    <div @if($tenant && $tenant->cccd_front) @click="openModal = true; modalImg = '{{ asset('storage/'.$tenant->cccd_front) }}'" @endif
                        class="group flex items-center gap-4 p-3 rounded-2xl border border-slate-50 hover:border-indigo-100 hover:bg-indigo-50/50 transition-all {{ ($tenant && $tenant->cccd_front) ? 'cursor-pointer' : 'opacity-50' }}">
                        <div class="w-16 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                            @if($tenant && $tenant->cccd_front)
                                <img src="{{ asset('storage/'.$tenant->cccd_front) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-300">N/A</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Mặt trước</p>
                            <p class="text-xs font-bold text-slate-700">{{ ($tenant && $tenant->cccd_front) ? 'Xem ảnh' : 'Chưa có' }}</p>
                        </div>
                        @if($tenant && $tenant->cccd_front) <span class="text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">🔍</span> @endif
                    </div>

                    {{-- Mặt sau --}}
                    <div @if($tenant && $tenant->cccd_back) @click="openModal = true; modalImg = '{{ asset('storage/'.$tenant->cccd_back) }}'" @endif
                        class="group flex items-center gap-4 p-3 rounded-2xl border border-slate-50 hover:border-indigo-100 hover:bg-indigo-50/50 transition-all {{ ($tenant && $tenant->cccd_back) ? 'cursor-pointer' : 'opacity-50' }}">
                        <div class="w-16 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                            @if($tenant && $tenant->cccd_back)
                                <img src="{{ asset('storage/'.$tenant->cccd_back) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-300">N/A</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Mặt sau</p>
                            <p class="text-xs font-bold text-slate-700">{{ ($tenant && $tenant->cccd_back) ? 'Xem ảnh' : 'Chưa có' }}</p>
                        </div>
                        @if($tenant && $tenant->cccd_back) <span class="text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">🔍</span> @endif
>>>>>>> feb1f02 (first commit)
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
    </div>

    <div x-show="openModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md"
         @keydown.escape.window="openModal = false"
         style="display: none;">
        
        <div class="absolute top-5 right-5 z-[110]">
            <button @click="openModal = false" class="bg-white/10 hover:bg-white/20 text-white p-3 rounded-full backdrop-blur-xl transition-all border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="relative max-w-4xl w-full flex justify-center items-center" @click.away="openModal = false">
            <img :src="modalImg" class="max-w-full max-h-[85vh] rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.5)] border-4 border-white/10 object-contain">
=======
        {{-- Cột phải: Thông tin chi tiết --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-sm h-full">
                <div class="flex items-center gap-3 mb-10 text-slate-800">
                    <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-xl font-black tracking-tight">Thông tin chi tiết</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Liên hệ</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm">📞</span>
                            {{ $tenant->phone ?? '---' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ngày sinh</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm">📅</span>
                            {{ $tenant->birthday ?? '---' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Giới tính</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm">👤</span>
                            {{ $tenant?->gender == 'male' ? 'Nam' : ($tenant?->gender == 'female' ? 'Nữ' : ($tenant?->gender == 'other' ? 'Khác' : '---')) }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nghề nghiệp</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm">💼</span>
                            {{ $tenant->job ?? '---' }}
                        </p>
                    </div>

                    <div class="md:col-span-2 space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Địa chỉ thường trú</label>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 text-slate-700 font-bold italic leading-relaxed">
                            {{ $tenant->hometown ?? 'Thông tin chưa được cập nhật...' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL XEM ẢNH --}}
    <div x-show="openModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm"
         @click="openModal = false"
         @keydown.escape.window="openModal = false">
        <div class="relative max-w-4xl w-full flex justify-center items-center">
            <img :src="modalImg" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl border-4 border-white object-contain bg-white">
            <button type="button"
                    @click.stop="openModal = false"
                    class="absolute -top-12 right-0 text-white text-3xl font-light hover:rotate-90 transition-transform">
                ✕
            </button>
        </div>
    </div>

    {{-- MODAL CHỈNH SỬA HỒ SƠ --}}
    <div x-show="openEdit" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/40">

        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
             @click.away="openEdit = false">

            {{-- Header sticky --}}
            <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Chỉnh sửa hồ sơ</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">
                        Cập nhật thông tin cá nhân
                    </p>
                </div>
                <button type="button"
                        @click="openEdit = false"
                        class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                    ✕
                </button>
            </div>

            {{-- Body scroll --}}
            <div class="p-6 overflow-y-auto modal-scroll">
                <form action="{{ route('tenant.profile.update') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Họ và tên</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full p-3 bg-slate-100 rounded-xl font-bold text-slate-400 border border-transparent cursor-not-allowed">
                            <p class="text-[11px] text-slate-400 mt-1 italic">Email không thể chỉnh sửa</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', $tenant->phone ?? '') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Giới tính</label>
                            <select name="gender"
                                    class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="male" {{ old('gender', $tenant->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender', $tenant->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ old('gender', $tenant->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Ngày sinh</label>
                            <input type="date" name="birthday" value="{{ old('birthday', $tenant->birthday ?? '') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Nghề nghiệp</label>
                            <input type="text" name="job" value="{{ old('job', $tenant->job ?? '') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-500 mb-1 block">Địa chỉ thường trú</label>
                            <input type="text" name="hometown" value="{{ old('hometown', $tenant->hometown ?? '') }}"
                                   class="w-full p-3 bg-slate-50 rounded-xl font-bold text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">CCCD mặt trước</label>
                            <input type="file" name="cccd_front"
                                   class="w-full p-3 bg-slate-50 rounded-xl text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 mb-1 block">CCCD mặt sau</label>
                            <input type="file" name="cccd_back"
                                   class="w-full p-3 bg-slate-50 rounded-xl text-slate-700 border border-transparent focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Footer sticky --}}
                    <div class="flex gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white">
                        <button type="submit"
                                class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl font-black uppercase tracking-widest text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                            Lưu thay đổi
                        </button>
                        <button type="button"
                                @click="openEdit = false"
                                class="px-8 text-slate-400 font-bold hover:text-slate-600">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
>>>>>>> feb1f02 (first commit)
        </div>
    </div>
</div>

<style>
<<<<<<< HEAD
    /* Tránh hiện tượng Flash content trước khi Alpine load */
    [x-cloak] { display: none !important; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.6s ease-out forwards;
    }
    body {
        -webkit-font-smoothing: antialiased;
        letter-spacing: -0.01em;
    }
=======
    [x-cloak] { display: none !important; }
    body { background-color: #fcfdfe; -webkit-font-smoothing: antialiased; }
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .modal-scroll::-webkit-scrollbar { width: 5px; }
    .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
    .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .animate-fadeIn { animation: fadeIn 0.4s ease-out; }
>>>>>>> feb1f02 (first commit)
</style>
@endsection