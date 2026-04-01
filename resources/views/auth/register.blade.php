<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Đăng ký tài khoản</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label class="block mb-1">Họ tên</label>
        <input type="text" name="name" required class="w-full border p-2 rounded mb-3">

        <label class="block mb-1">Email</label>
        <input type="email" name="email" required class="w-full border p-2 rounded mb-3">

        <label class="block mb-1">Mật khẩu</label>
        <input type="password" name="password" required class="w-full border p-2 rounded mb-3">

        <label class="block mb-1">Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" required class="w-full border p-2 rounded mb-4">

        <button class="w-full bg-black text-white py-2 rounded">
            Đăng ký
        </button>
    </form>

    <p class="text-center mt-4 text-sm">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="text-blue-600">Đăng nhập</a>
    </p>
</div>

</body>
</html>
