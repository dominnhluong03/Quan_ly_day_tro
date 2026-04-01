<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý phòng trọ - Hiện đại</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Phối màu gradient sâu, hiện đại */
            background: linear-gradient(135deg, #1A202C 0%, #2D3748 50%, #1A202C 100%);
            background-size: 200% 200%;
            animation: gradientMove 15s ease infinite;
            overflow-x: hidden; /* Tránh scroll ngang khi có các hiệu ứng */
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.08); /* Nền hơi trong suốt */
            backdrop-filter: blur(15px); /* Hiệu ứng kính mờ */
            border: 1px solid rgba(255, 255, 255, 0.1); /* Viền mỏng */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); /* Đổ bóng sâu */
            transform-style: preserve-3d; /* Kích hoạt 3D */
            transition: all 0.3s ease-out;
        }

        .gradient-text {
            background: linear-gradient(90deg, #8B5CF6, #EC4899); /* Tím hồng */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(90deg, #8B5CF6, #EC4899); /* Nút gradient */
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }
        .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
            transform: translateY(-2px);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.2;
            z-index: -1;
            animation: orbFloat 20s infinite alternate ease-in-out;
        }

        @keyframes orbFloat {
            0% { transform: translate(-20%, -20%) scale(1); }
            50% { transform: translate(10%, 10%) scale(1.1); }
            100% { transform: translate(-20%, -20%) scale(1); }
        }
    </style>
</head>

<body class="relative min-h-screen flex flex-col">

<div class="orb bg-indigo-500 w-96 h-96 top-[-10%] left-[-10%]"></div>
<div class="orb bg-purple-600 w-80 h-80 bottom-[-10%] right-[-10%] animation-delay-2000" style="animation-delay: -5s;"></div>
<div class="orb bg-pink-500 w-72 h-72 top-[20%] right-[5%] animation-delay-4000" style="animation-delay: -10s;"></div>


<section class="text-center py-20 relative z-10">
    <h1 class="text-5xl font-extrabold gradient-text mb-4 leading-tight">
        Quản Lý Phòng Trọ Hiện Đại
    </h1>
    <p class="text-gray-300 max-w-3xl mx-auto mb-10 text-lg">
        Giải pháp toàn diện giúp bạn quản lý tòa nhà, khách thuê, hợp đồng, hóa đơn và thanh toán một cách thông minh, hiệu quả và tối ưu thời gian.
    </p>

    <div class="flex justify-center gap-6">
        <a href="{{ route('login') }}"
           class="btn-primary text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl">
            Đăng nhập ngay
        </a>

<<<<<<< HEAD
        <a href="#"
           class="bg-white/10 border border-white/20 text-white px-8 py-3 rounded-xl font-semibold hover:bg-white/20 transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Xem phòng trống
        </a>
=======
>>>>>>> feb1f02 (first commit)
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 pb-20 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Quản lý tòa nhà</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Nền tảng giúp bạn quản lý thông tin chi tiết về từng tòa nhà, tiện ích và tổng số lượng phòng một cách rõ ràng.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-teal-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2V3a1 1 0 00-1-1H8a1 1 0 00-1 1v2H5a2 2 0 00-2 2v11a2 2 0 002 2h2m0 0l4-4m-4 4l-4-4m0 0v-5m0 5h8" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Quản lý khách thuê</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Lưu trữ và truy xuất thông tin khách thuê, giấy tờ tùy thân, nghề nghiệp và lịch sử thuê phòng mọi lúc mọi nơi.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Quản lý hợp đồng</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Tạo, chỉnh sửa và theo dõi trạng thái hợp đồng thuê phòng với các điều khoản rõ ràng, đảm bảo minh bạch.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-6 6l-6-6m6 6l6-6m-6 6l-6-6M9 14l6-6m-6 6l-6-6m6 6l6-6m-6 6l-6-6M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Quản lý hóa đơn</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Tạo hóa đơn tự động hàng tháng, tính toán chính xác tiền điện, nước và các dịch vụ khác cho từng phòng.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Quản lý thanh toán</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Theo dõi tình trạng thanh toán, ghi nhận các khoản thu và tự động gửi nhắc nhở đến khách thuê khi đến hạn.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-500 to-amber-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Chỉ số điện nước</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Ghi nhận và tính toán lượng tiêu thụ điện, nước hàng tháng một cách minh bạch, tránh sai sót.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-pink-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Quản lý sự cố</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Tiếp nhận, theo dõi và xử lý nhanh chóng các yêu cầu bảo trì, sự cố từ khách thuê, đảm bảo hài lòng.
            </p>
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Hệ thống thông báo</h3>
            
        </div>

        <div class="glass-card p-8 rounded-3xl cursor-pointer" data-tilt data-tilt-max="10" data-tilt-speed="400">
            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-500 to-gray-700 mb-6 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="font-bold text-xl text-white mb-2">Cài đặt hệ thống</h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                Tùy chỉnh các thông số hệ thống, quản lý tài khoản người dùng và thực hiện sao lưu, khôi phục dữ liệu định kỳ.
            </p>
        </div>

    </div>
</section>

</body>
</html>