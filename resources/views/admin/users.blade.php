@extends('layouts.admin')

@section('title', 'Mas\'ul Xodimlar')

@section('content')
    <div class="flex flex-col gap-8">
        
        <!-- 1. Yangi xodim qo'shish formasi -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 text-lg">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-user-plus"></i>
                </div>
                Yangi Xodim Qo'shish
            </h3>
            
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">F.I.SH</label>
                        <input type="text" name="name" placeholder="Masalan: Aziz Karimov" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Bo'lim / Lavozim</label>
                        <input type="text" name="department" placeholder="Toshkent shahar bo'limi" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Telefon</label>
                        <input type="text" name="phone" placeholder="+998 90 123 45 67" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Login (Email)</label>
                        <input type="email" name="email" placeholder="aziz@ishonch.uz" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Parol</label>
                        <input type="password" name="password" placeholder="********" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                </div>
                
                <button class="w-full md:w-auto px-8 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> SAQLASH
                </button>
            </form>
        </div>

        <!-- 2. Xodimlar Ro'yxati (Kartochkalar) -->
        <div>
            <h3 class="font-bold text-slate-800 mb-4 text-lg">Xodimlar Ro'yxati</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($users as $user)
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition group relative">
                    
                    <!-- O'chirish tugmasi (Hov qilinganda chiqadi) -->
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Rostdan ham bu xodimni o\'chirmoqchimisiz?');" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        @csrf
                        @method('DELETE')
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>

                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 text-xl font-bold border-2 border-white shadow-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-slate-800 truncate" title="{{ $user->name }}">{{ $user->name }}</h4>
                            <span class="inline-block px-2 py-0.5 bg-yellow-50 text-yellow-700 rounded text-[10px] font-bold border border-yellow-100 uppercase tracking-wide mt-1">
                                {{ $user->department ?? 'Bo\'limsiz' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2.5 border-t border-slate-50 pt-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-medium"><i class="fas fa-phone w-4"></i> Telefon:</span>
                            <span class="font-bold text-slate-600">{{ $user->phone }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-medium"><i class="fas fa-envelope w-4"></i> Email:</span>
                            <span class="font-bold text-slate-600 truncate max-w-[150px]">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs pt-1">
                            <span class="px-2 py-1 bg-green-50 text-green-600 rounded-md text-[10px] font-bold border border-green-100 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> AKTIV
                            </span>
                            <span class="text-[10px] text-slate-300 font-mono">{{ $user->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($users->isEmpty())
                <div class="col-span-full text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-400">
                    <i class="fas fa-users text-4xl mb-3 opacity-50"></i>
                    <p>Hozircha qo'shimcha xodimlar yo'q</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection