<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kirish</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border border-slate-200">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mx-auto mb-3">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800">Ishonch Nazorati</h2>
            <p class="text-xs text-slate-400 uppercase tracking-wider">Tizimga kirish</p>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> Login yoki parol noto'g'ri!
            </div>
        @endif

        <form action="{{ route('admin.auth') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-slate-600 text-xs font-bold mb-2 uppercase">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-slate-400"></i>
                    <input type="email" name="email" value="admin@ishonch.uz" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-slate-600 text-xs font-bold mb-2 uppercase">Parol</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-slate-400"></i>
                    <input type="password" name="password" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                </div>
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all active:scale-95">
                KIRISH
            </button>
        </form>
    </div>
</body>
</html>