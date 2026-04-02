<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Tùy chỉnh thanh cuộn cho mượt mà (giống bản Tenant) */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Chống giật trang khi load */
        [x-cloak] { display: none !important; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</head>

<body class="bg-slate-100 font-sans h-screen overflow-hidden">

<div class="flex h-full">

    <aside class="w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white shadow-xl flex flex-col shrink-0 transition-all duration-300">

        <div class="px-6 py-5 border-b border-slate-700 shrink-0">
            <h1 class="text-xl font-bold tracking-tight text-blue-400">

                 Quản Lý Phòng Trọ

            </h1>
        </div>

        <nav class="p-4 space-y-1 text-sm flex-1 overflow-y-auto custom-scrollbar">
            @php
                $menu = [
                    ['route' => 'admin.dashboard', 'name' => 'Trang chủ', 'icon' => '🏠'],
                    ['route' => 'admin.users.index', 'name' => 'Tài khoản', 'icon' => '👤'],
                    ['route' => 'admin.buildings.index', 'name' => 'Tòa nhà', 'icon' => '🏢'],
                    ['route' => 'admin.rooms.index', 'name' => 'Phòng', 'icon' => '🚪'],
                    ['route' => 'admin.tenants.index', 'name' => 'Khách thuê', 'icon' => '👥'],
                    ['route' => 'admin.contracts.index', 'name' => 'Hợp đồng', 'icon' => '📜'],
                    ['route' => 'admin.bills.index', 'name' => 'Hóa đơn', 'icon' => '🧾'],
                    ['route' => 'admin.payments.index', 'name' => 'Thanh toán', 'icon' => '💳'],
                    ['route' => 'admin.issues.index', 'name' => 'Sự cố', 'icon' => '⚠️'],
                    ['route' => 'admin.password.index', 'name' => 'Đổi mật khẩu', 'icon' => '🔐'],
                ];
            @endphp

            @foreach($menu as $item)
                <a href="{{ route($item['route']) }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                   {{ request()->routeIs($item['route'].'*')
                        ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20'
                        : 'text-slate-300 hover:bg-slate-700/50 hover:text-white'
                   }}">
                    <span class="text-lg group-hover:scale-110 transition">{{ $item['icon'] }}</span>
                    <span class="font-medium tracking-wide">{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>
        
        <div class="p-4 border-t border-slate-700 text-[10px] text-slate-500 text-center uppercase tracking-widest">
            Admin Panel v1.0
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">

        <header class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 shadow-lg px-8 py-4 flex justify-between items-center text-white shrink-0 z-10">
            
            <div>
                <h2 class="text-xl font-bold tracking-tight">
                    @yield('page_title','Dashboard')
                </h2>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">
                    Administrator Space
                </p>
            </div>

            <div class="relative group py-2"> 
                <div class="flex items-center gap-3 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-full transition-all cursor-pointer border border-white/10">
                    <div class="w-8 h-8 rounded-full bg-white text-indigo-600 flex items-center justify-center font-black shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-bold hidden md:block">{{ auth()->user()->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 group-hover:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div class="absolute right-0 top-full pt-1 w-60 opacity-0 scale-95 translate-y-2 group-hover:opacity-100 group-hover:scale-100 group-hover:translate-y-0 transition-all duration-200 pointer-events-none group-hover:pointer-events-auto z-50">
                    <div class="bg-white text-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.15)] rounded-2xl overflow-hidden border border-slate-100">
                        <div class="p-4 border-b bg-slate-50/80">
                            <p class="font-black text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-400 truncate mt-1 font-medium">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-2 text-sm">
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <span>⚙️</span> Cài đặt hệ thống
                            </a>
                            <div class="my-1 border-t border-slate-50"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2.5 rounded-xl text-red-500 hover:bg-red-50 font-black flex items-center gap-3 transition-colors">
                                    <span class="text-lg text-red-400">🚪</span> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto custom-scrollbar bg-[#f8fafc]">
            <div class="p-6 md:p-8 animate-fadeIn">
                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>