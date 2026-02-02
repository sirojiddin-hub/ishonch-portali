<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ishonch Nazorati - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">
    
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col justify-between">
        <div>
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <i class="fas fa-shield-alt text-emerald-600 text-2xl mr-3"></i>
                <div>
                    <h1 class="font-bold text-gray-800 text-sm uppercase">Ishonch Nazorati</h1>
                    <p class="text-[10px] text-gray-400">Yagona Portal</p>
                </div>
            </div>

            <!-- Menu -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-600 font-medium' : '' }}">
                    <i class="fas fa-th-large w-5"></i> Monitoring
                </a>
                
                <a href="{{ route('admin.appeals') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition {{ request()->routeIs('admin.appeals') ? 'bg-emerald-50 text-emerald-600 font-medium' : '' }}">
                    <i class="fas fa-inbox w-5"></i> Arizalar
                    <span class="ml-auto bg-emerald-100 text-emerald-600 text-xs font-bold px-2 py-0.5 rounded-lg">{{ \App\Models\Appeal::where('status', 'new')->count() }}</span>
                </a>

                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition {{ request()->routeIs('admin.categories') ? 'bg-emerald-50 text-emerald-600 font-medium' : '' }}">
                    <i class="fas fa-list w-5"></i> Kategoriyalar
                </a>

                <a href="{{ route('admin.statistics') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition {{ request()->routeIs('admin.statistics') ? 'bg-emerald-50 text-emerald-600 font-bold' : '' }}">
                    <i class="fas fa-chart-pie w-5 text-center"></i> Statistika
                </a>

                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition {{ request()->routeIs('admin.users') ? 'bg-emerald-50 text-emerald-600 font-bold' : '' }}">
                    <i class="fas fa-users w-5 text-center"></i> Mas'ul Xodimlar
                </a>

                <a href="{{ route('admin.archive') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition {{ request()->routeIs('admin.archive*') ? 'bg-emerald-50 text-emerald-600 font-bold' : '' }}">
                    <i class="fas fa-archive w-5 text-center"></i> Arxiv
                </a>
            </nav>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-100">
            <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</h4>
                    <p class="text-[10px] text-gray-400">Administrator</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="ml-auto">
                    @csrf
                    <button class="text-gray-400 hover:text-red-500"><i class="fas fa-sign-out-alt"></i></button>
                </form>
            </div>
        </div>
    </aside>

    <!-- CONTENT AREA -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40">
            <h2 class="text-xl font-bold text-gray-800">@yield('title')</h2>
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-400 font-mono">{{ now()->format('H:i:s | d.m.Y') }}</span>
                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-xs font-bold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Tizim Onlayn
                </span>
            </div>
        </header>

        <!-- Content -->
        <div class="p-8">
            @yield('content')
        </div>
    </main>
</div>

</body>
</html>