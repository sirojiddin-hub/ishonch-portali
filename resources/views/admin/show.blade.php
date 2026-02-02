@extends('layouts.admin')

@section('title', 'Ariza Tafsilotlari')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('admin.appeals') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 mb-6 text-sm font-medium transition">
            <i class="fas fa-arrow-left"></i> Ortga qaytish
        </a>
        <a href="{{ route('admin.print', $appeal->id) }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-700 transition shadow-md">
                <i class="fas fa-print"></i> CHOP ETISH
            </a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Chap tomon: Ma'lumotlar -->
            <div class="md:col-span-2 space-y-6">
                <!-- Asosiy Kartochka -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-xs font-mono text-slate-400 block mb-1">ID Raqam</span>
                            <h2 class="text-2xl font-bold text-slate-800 font-mono">{{ $appeal->track_code }}</h2>
                        </div>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ $appeal->created_at->format('d.m.Y | H:i') }}</span>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Muammo Turi</span>
                                <span class="text-sm font-bold text-slate-700">{{ $appeal->type }}</span>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Hudud</span>
                                <span class="text-sm font-bold text-slate-700">{{ $appeal->region }}</span>
                                <span class="text-xs text-slate-500 block">{{ $appeal->district }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-2">Murojaat Matni</span>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $appeal->description }}</div>
                        </div>
                        
                        @if($appeal->organization)
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Tashkilot</span>
                            <span class="text-sm font-medium text-slate-800">{{ $appeal->organization }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Fayllar -->
                @if($appeal->files)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-paperclip text-slate-400"></i> Ilova qilingan fayllar
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($appeal->files as $file)
                            <a href="{{ asset('storage/'.$file) }}" target="_blank" class="block p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 hover:shadow-md transition group text-center">
                                <i class="fas fa-file-download text-2xl text-slate-300 group-hover:text-blue-500 mb-2 transition"></i>
                                <span class="block text-xs font-medium text-slate-600 group-hover:text-blue-700 truncate">Yuklab olish</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- O'ng tomon: Boshqaruv -->
            <div class="space-y-6">



                 <!-- YANGI QISM: MAS'UL XODIM TAYINLASH -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase flex items-center gap-2">
            <i class="fas fa-user-tie text-slate-400"></i> Mas'ul Xodim
        </h3>

        @if($assignedUser)
            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100 mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 font-bold">
                    {{ substr($assignedUser->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-sm font-bold text-slate-700 truncate">{{ $assignedUser->name }}</h4>
                    <p class="text-[10px] text-slate-400 uppercase">{{ $assignedUser->department }}</p>
                </div>
            </div>
        @else
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 mb-4 text-center">
                <p class="text-xs text-slate-400 italic">Hozircha hech kim biriktirilmagan</p>
            </div>
        @endif

        <form action="{{ route('admin.assign', $appeal->id) }}" method="POST">
            @csrf
            <div class="flex gap-2">
                <select name="user_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="null">-- Biriktirishni bekor qilish --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $appeal->assigned_to == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->department }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-slate-800 text-white px-3 rounded-lg hover:bg-slate-700">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </form>
    </div>
                


                <!-- Status O'zgartirish -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm sticky top-24">
                    <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase">Murojaat Holati</h3>
                    <form action="{{ route('admin.update', $appeal->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Status</label>
                            <select name="status" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="new" {{ $appeal->status == 'new' ? 'selected' : '' }}>Yangi</option>
                                <option value="processing" {{ $appeal->status == 'processing' ? 'selected' : '' }}>Jarayonda</option>
                                <option value="closed" {{ $appeal->status == 'closed' ? 'selected' : '' }}>Yopilgan (Hal etildi)</option>
                                <option value="rejected" {{ $appeal->status == 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Admin Izohi</label>
                            <textarea name="admin_note" rows="4" placeholder="Izoh qoldiring..." class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">{{ $appeal->admin_note }}</textarea>
                        </div>
                        <button class="w-full py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition">
                            SAQLASH
                        </button>
                    </form>
                </div>

                <!-- Foydalanuvchi -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase">Ariza Beruvchi</h3>
                    @if($appeal->is_anonymous)
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-emerald-700">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <i class="fas fa-user-secret"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold">Anonim</span>
                                <span class="text-[10px] opacity-75">Shaxsiy ma'lumotlar yashirilgan</span>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 uppercase">F.I.SH</span>
                                    <span class="text-sm font-bold text-slate-700">{{ $appeal->full_name }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 uppercase">Telefon</span>
                                    <a href="tel:{{ $appeal->phone }}" class="text-sm font-bold text-blue-600 hover:underline">{{ $appeal->phone }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection