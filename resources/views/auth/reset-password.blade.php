<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: radial-gradient(circle at center, #1e1b4b 0%, #020617 100%); }
        .glass-card { background: rgba(255,255,255,.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,.1); box-shadow: 0 25px 50px -12px rgba(0,0,0,.5); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">

<div class="glass-card p-10 rounded-[2.5rem] w-full max-w-[420px]">
    <h2 class="text-3xl font-bold text-white text-center tracking-tight mb-2">Đặt lại mật khẩu</h2>
    <p class="text-indigo-300/60 text-center text-sm mb-8">Tạo mật khẩu mới cho tài khoản của bạn</p>

    @if ($errors->any())
        <div class="mb-5 text-sm text-rose-200 bg-rose-500/10 border border-rose-400/20 rounded-2xl p-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ old('email', $email) }}">

        <div>
            <label class="block text-xs font-semibold text-indigo-300 uppercase tracking-widest mb-2 ml-1">Mật khẩu mới</label>
            <input type="password" name="password" required placeholder="••••••••"
                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white outline-none focus:border-indigo-500/50 focus:bg-white/10 transition-all">
        </div>

        <div>
            <label class="block text-xs font-semibold text-indigo-300 uppercase tracking-widest mb-2 ml-1">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" required placeholder="••••••••"
                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white outline-none focus:border-indigo-500/50 focus:bg-white/10 transition-all">
        </div>

        <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all active:scale-95">
            Cập nhật mật khẩu
        </button>

        <a href="{{ route('login') }}" class="block text-center text-sm text-white/50 hover:text-white mt-2">
            ← Quay lại đăng nhập
        </a>
    </form>
</div>

</body>
</html>