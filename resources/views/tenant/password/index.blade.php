@extends('tenant.layout')

@section('title','Đổi mật khẩu')
@section('page_title','Bảo mật tài khoản')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-4xl mx-auto py-8 animate-fadeIn">
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-5/12 bg-slate-900 p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 border border-white/20">
                    <span class="text-2xl">🛡️</span>
                </div>
                <h2 class="text-3xl font-black leading-tight tracking-tight">Bảo vệ <br>không gian của bạn.</h2>
                <p class="mt-4 text-slate-400 text-sm font-medium leading-relaxed">
                    Việc thay đổi mật khẩu định kỳ giúp tăng cường bảo mật cho tài khoản khách thuê của bạn.
                </p>
            </div>

            <div class="relative z-10 mt-12">
                <div class="flex items-center gap-3 text-xs font-bold text-indigo-400 uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Hệ thống mã hóa AES-256
                </div>
            </div>
        </div>

        <div class="md:w-7/12 p-8 md:p-12 bg-white">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold flex items-center gap-2">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.password.update.index') }}" class="space-y-6">
                @csrf

                <div class="space-y-2" x-data="{ show: false }">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.1em] ml-1">Mật khẩu hiện tại</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password" 
                            class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-5 py-3.5 font-bold text-slate-700 transition-all focus:bg-white focus:border-indigo-500/30 outline-none @error('current_password') border-rose-100 bg-rose-50/50 @enderror"
                            placeholder="Nhập mật khẩu cũ">
                        
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.888 9.888L3 3m18 18l-6.888-6.888"/></svg>
                        </button>
                    </div>
                    @error('current_password') <p class="text-[11px] font-bold text-rose-500 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2" x-data="{ show: false }">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.1em] ml-1">Mật khẩu mới</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="new_password" 
                            class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-5 py-3.5 font-bold text-slate-700 transition-all focus:bg-white focus:border-indigo-500/30 outline-none @error('new_password') border-rose-100 bg-rose-50/50 @enderror"
                            placeholder="Tối thiểu 8 ký tự">
                        
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.888 9.888L3 3m18 18l-6.888-6.888"/></svg>
                        </button>
                    </div>
                    @error('new_password') <p class="text-[11px] font-bold text-rose-500 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2" x-data="{ show: false }">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.1em] ml-1">Xác nhận mật khẩu mới</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="new_password_confirmation" 
                            class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-5 py-3.5 font-bold text-slate-700 transition-all focus:bg-white focus:border-indigo-500/30 outline-none"
                            placeholder="Nhập lại mật khẩu mới">
                        
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.888 9.888L3 3m18 18l-6.888-6.888"/></svg>
                        </button>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-slate-900 transition-all hover:-translate-y-1 active:scale-95">
                        Cập nhật mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.6s cubic-bezier(0.2, 1, 0.2, 1) forwards; }
    
    body { background-color: #fcfcfd; -webkit-font-smoothing: antialiased; }
</style>
@endsection