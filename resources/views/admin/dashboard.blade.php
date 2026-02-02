@extends('layouts.admin')

@section('title', 'Monitoring')

@section('content')
    <!-- Statistika Vidjetlari -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Yangi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-emerald-300 transition cursor-pointer" onclick="window.location='{{ route('admin.appeals', ['status' => 'new']) }}'">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Yangi Arizalar</span>
                <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['new'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition">
                <i class="fas fa-inbox text-xl"></i>
            </div>
        </div>

        <!-- Jarayonda -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-yellow-300 transition cursor-pointer" onclick="window.location='{{ route('admin.appeals', ['status' => 'processing']) }}'">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jarayonda</span>
                <h3 class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['processing'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-500 group-hover:bg-yellow-500 group-hover:text-white transition">
                <i class="fas fa-sync-alt text-xl"></i>
            </div>
        </div>

        <!-- Yopilgan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-slate-300 transition cursor-pointer" onclick="window.location='{{ route('admin.appeals', ['status' => 'closed']) }}'">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hal Etilgan</span>
                <h3 class="text-3xl font-bold text-slate-700 mt-1">{{ $stats['closed'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-slate-700 group-hover:text-white transition">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>

        <!-- Bugungi -->
        <div class="bg-blue-600 p-5 rounded-2xl border border-blue-500 shadow-lg shadow-blue-200 flex items-center justify-between text-white relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider">Bugun tushgan</span>
                <h3 class="text-3xl font-bold mt-1">+{{ $todayCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white relative z-10">
                <i class="fas fa-calendar-day text-xl"></i>
            </div>
            <!-- Orqa fon bezagi -->
            <i class="fas fa-chart-line absolute -bottom-2 -right-2 text-8xl text-white/10"></i>
        </div>
    </div>

    <!-- Oxirgi Arizalar Jadvali -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">So'nggi kelib tushgan arizalar</h3>
            <a href="{{ route('admin.appeals') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                BARCHASINI KO'RISH <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase">ID</th>
                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase">Muammo</th>
                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase">Hudud</th>
                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase">Vaqt</th>
                    <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($latestAppeals as $appeal)
                <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location='{{ route('admin.show', $appeal->id) }}'">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">{{ $appeal->track_code }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700">{{ $appeal->type }}</td>
                    <td class="px-6 py-4 text-xs text-slate-500">{{ $appeal->region }}</td>
                    <td class="px-6 py-4 text-xs text-slate-400">{{ $appeal->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($appeal->status == 'new')
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded text-[10px] font-bold uppercase">Yangi</span>
                        @elseif($appeal->status == 'processing')
                            <span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded text-[10px] font-bold uppercase">Jarayonda</span>
                        @else
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-[10px] font-bold uppercase">Yopilgan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">Hozircha arizalar yo'q</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection