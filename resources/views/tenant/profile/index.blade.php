@extends('tenant.layout')

@section('title', 'Hồ sơ cá nhân')
@section('page_title', 'Thông tin cá nhân')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-6xl mx-auto px-4 py-8 animate-fadeIn"
     x-data="{ openModal: false, modalImg: '', openEdit: false }">

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm">
            <span class="text-lg">✨</span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-blue-500 opacity-10"></div>

                <div class="relative inline-block mt-4">
                    <div class="w-28 h-28 rounded-3xl shadow-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white text-4xl font-black mx-auto mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="absolute bottom-6 right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full"></div>
                </div>

                <h1 class="text-2xl font-bold text-slate-800">{{ $user->name }}</h1>
                <p class="text-slate-500 text-sm mb-8">{{ $user->email }}</p>

                <button type="button"
                        @click="openEdit = true"
                        class="w-full py-4 px-6 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-indigo-100">
                    Chỉnh sửa hồ sơ
                </button>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Định danh cá nhân</h3>
                    <span class="text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded-lg italic">Verified</span>
                </div>

                <div class="space-y-3">
                    <div
                        @if($tenant && $tenant->cccd_front)
                            @click="openModal = true; modalImg = '{{ asset('storage/' . $tenant->cccd_front) }}'"
                        @endif
                        class="group flex items-center gap-4 p-3 rounded-2xl border border-slate-100 hover:border-indigo-100 hover:bg-indigo-50/50 transition-all {{ ($tenant && $tenant->cccd_front) ? 'cursor-pointer' : 'opacity-60' }}">
                        <div class="w-16 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                            @if($tenant && $tenant->cccd_front)
                                <img src="{{ asset('storage/' . $tenant->cccd_front) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-300">N/A</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Mặt trước</p>
                            <p class="text-xs font-bold text-slate-700">{{ ($tenant && $tenant->cccd_front) ? 'Xem ảnh' : 'Chưa có' }}</p>
                        </div>
                        @if($tenant && $tenant->cccd_front)
                            <span class="text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">🔍</span>
                        @endif
                    </div>

                    <div
                        @if($tenant && $tenant->cccd_back)
                            @click="openModal = true; modalImg = '{{ asset('storage/' . $tenant->cccd_back) }}'"
                        @endif
                        class="group flex items-center gap-4 p-3 rounded-2xl border border-slate-100 hover:border-indigo-100 hover:bg-indigo-50/50 transition-all {{ ($tenant && $tenant->cccd_back) ? 'cursor-pointer' : 'opacity-60' }}">
                        <div class="w-16 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                            @if($tenant && $tenant->cccd_back)
                                <img src="{{ asset('storage/' . $tenant->cccd_back) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-300">N/A</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Mặt sau</p>
                            <p class="text-xs font-bold text-slate-700">{{ ($tenant && $tenant->cccd_back) ? 'Xem ảnh' : 'Chưa có' }}</p>
                        </div>
                        @if($tenant && $tenant->cccd_back)
                            <span class="text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">🔍</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

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
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hộp thư</label>
                        <p class="text-base font-bold text-slate-700 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-sm">✉️</span>
                            {{ $user->email }}
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

    <div x-show="openEdit"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/40">

        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
             @click.away="openEdit = false">

            <div class="p-6 border-b flex items-center justify-between bg-white sticky top-0 z-10">
                <div>
                    <h2 class="text-xl font-black text-slate-900">Chỉnh sửa hồ sơ</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-tight">Cập nhật thông tin cá nhân</p>
                </div>
                <button type="button"
                        @click="openEdit = false"
                        class="text-slate-400 hover:text-rose-500 font-black text-xl transition-colors">
                    ✕
                </button>
            </div>

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
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    body {
        background-color: #fcfdfe;
        -webkit-font-smoothing: antialiased;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out;
    }

    .modal-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .modal-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .modal-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
@endsection