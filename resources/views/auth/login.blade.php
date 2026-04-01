<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at center, #1e1b4b 0%, #020617 100%);
            overflow: hidden;
        }
        .blob {
            position: absolute;
            width: 520px;
            height: 520px;
            background: linear-gradient(180deg, rgba(79, 70, 229, 0.15) 0%, rgba(147, 51, 234, 0.15) 100%);
            filter: blur(90px);
            border-radius: 50%;
            z-index: -1;
            animation: move 20s infinite alternate;
        }
        @keyframes move {
            from { transform: translate(-10%, -10%) rotate(0deg); }
            to { transform: translate(20%, 20%) rotate(360deg); }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.10);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.55);
            transform-style: preserve-3d;
        }
        .inner-3d { transform: translateZ(45px); }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen relative px-4">

    <div class="blob" style="top:-12%;left:-12%;"></div>
    <div class="blob" style="bottom:-12%;right:-12%;animation-delay:-5s;"></div>

    <div class="glass-card p-10 rounded-[2.5rem] w-full max-w-[440px] relative z-10"
         data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.2">

        <div class="inner-3d">

            {{-- Logo --}}
            <div class="flex justify-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-indigo-500/40 rotate-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white -rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-white text-center tracking-tight mb-2">Hệ Thống Quản Lý</h2>
            <p class="text-indigo-300/60 text-center text-sm mb-8">Vui lòng đăng nhập để tiếp tục</p>

            {{-- Thông báo thành công (vd: reset password xong) --}}
            @if (session('status'))
                <div class="mb-5 text-sm text-emerald-200 bg-emerald-500/10 border border-emerald-400/20 rounded-2xl p-4">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Thông báo lỗi --}}
            @if ($errors->any())
                <div class="mb-5 text-sm text-rose-200 bg-rose-500/10 border border-rose-400/20 rounded-2xl p-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-indigo-300 uppercase tracking-widest mb-2 ml-1">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="admin@phongtro.vn"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white outline-none
                               focus:border-indigo-500/50 focus:bg-white/10 transition-all"
                    >
                </div>

                <div>
                    <div class="flex justify-between mb-2 ml-1">
                        <label class="text-xs font-semibold text-indigo-300 uppercase tracking-widest">
                            Mật khẩu
                        </label>

                        {{-- QUÊN MẬT KHẨU: link chạy thật --}}
                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-400 hover:text-indigo-300">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <div class="relative">
                        <input
                            type="password"
                            id="passwordInput"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white outline-none
                                   focus:border-indigo-500/50 focus:bg-white/10 transition-all pr-12"
                        >

                        <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/30 hover:text-white transition-colors">
                            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>

                            <svg id="eyeClose" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.888 9.888L3 3m18 18l-6.888-6.888"/>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                        <input id="remember" name="remember" type="checkbox"
                               class="w-4 h-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-500/30">
                        <label for="remember" class="text-sm text-white/50">Ghi nhớ đăng nhập</label>
                    </div>
                </div>

                <button
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-600/30
                           transition-all active:scale-[0.98] flex justify-center items-center gap-2 group"
                >
                    Đăng Nhập
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

<<<<<<< HEAD
            <div class="mt-8 text-center">
                <p class="text-sm text-white/40">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-indigo-400 font-semibold hover:text-indigo-300">
                        Đăng ký
                    </a>
                </p>
            </div>
=======

>>>>>>> feb1f02 (first commit)

        </div>
    </div>

    <script>
        VanillaTilt.init(document.querySelectorAll(".glass-card"), {
            max: 8,
            speed: 400,
            glare: true,
            "max-glare": 0.1,
        });

        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const open = document.getElementById('eyeOpen');
            const close = document.getElementById('eyeClose');

            if (input.type === 'password') {
                input.type = 'text';
                open.classList.add('hidden');
                close.classList.remove('hidden');
            } else {
                input.type = 'password';
                open.classList.remove('hidden');
                close.classList.add('hidden');
            }
        }
    </script>

</body>
</html>