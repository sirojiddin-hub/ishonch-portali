@extends('layouts.admin')

@section('title', 'Kelib Tushgan Arizalar')

@section('content')
    <!-- Filter va Qidiruv -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 shadow-sm">
        
        <!-- Filter Tugmalari -->
        <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
            <!-- Barchasi tugmasi: Filter ham, Status ham bo'lmasa aktiv -->
            <a href="{{ route('admin.appeals') }}" class="px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap {{ !request('filter') && !request('status') ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Barchasi
            </a>
            
            <!-- Yangi tugmasi: Filter=new yoki Status=new bo'lsa aktiv -->
            <a href="{{ route('admin.appeals', ['filter' => 'new']) }}" class="px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap {{ request('filter') == 'new' || request('status') == 'new' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Yangi
            </a>
            
            <!-- Jiddiy tugmasi -->
            <a href="{{ route('admin.appeals', ['filter' => 'serious']) }}" class="px-4 py-2 rounded-lg text-xs font-bold whitespace-nowrap {{ request('filter') == 'serious' ? 'bg-red-600 text-white' : 'bg-white border border-red-200 text-red-600 hover:bg-red-50' }}">
                Jiddiy
            </a>
        </div>
        
        <!-- Qidiruv Formasi -->
        <form action="{{ route('admin.appeals') }}" method="GET" class="relative w-full md:w-72">
            <!-- Filter yoki Status saqlanib qolishi uchun yashirin input -->
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ID, muammo turi yoki hudud..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <button type="submit" class="absolute left-3 top-2.5 text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </button>
        </form>
    </div>

    <!-- Jadval -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] text-slate-400 uppercase border-b border-slate-200 tracking-wider">
                        <th class="px-6 py-4 font-bold">ID</th>
                        <th class="px-6 py-4 font-bold">Kategoriya</th>
                        <th class="px-6 py-4 font-bold">Hudud</th>
                        <th class="px-6 py-4 font-bold">Tafsilot</th>
                        <th class="px-6 py-4 font-bold">Mas'ul Xodim</th>
                        <th class="px-6 py-4 font-bold">Vaqt</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Amal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($appeals as $appeal)
                    <tr class="hover:bg-slate-50 transition group">
                        <!-- ID -->
                        <td class="px-6 py-4 font-mono text-slate-500 font-bold text-xs whitespace-nowrap">{{ $appeal->track_code }}</td>
                        
                        <!-- Kategoriya -->
                        <td class="px-6 py-4 font-bold text-slate-700 whitespace-nowrap">{{ $appeal->type }}</td>
                        
                        <!-- Hudud -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700 text-xs">{{ $appeal->region }}</div>
                            <div class="text-[10px] text-slate-400">{{ $appeal->district }}</div>
                        </td>
                        
                        <!-- Tafsilot -->
                        <td class="px-6 py-4 text-slate-500 text-xs max-w-[200px] truncate" title="{{ $appeal->description }}">
                            {{Str::limit($appeal->description, 30)}}
                        </td>
                        
                        <!-- Mas'ul Xodim -->
                        <td class="px-6 py-4">
                            @if($appeal->assignedUser)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600 border border-blue-200">
                                        {{ substr($appeal->assignedUser->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-medium text-slate-700 whitespace-nowrap">{{ $appeal->assignedUser->name }}</span>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-400 italic bg-slate-100 px-2 py-1 rounded">Biriktirilmagan</span>
                            @endif
                        </td>

                        <!-- Vaqt -->
                        <td class="px-6 py-4 text-slate-400 text-xs font-mono whitespace-nowrap">
                            {{ $appeal->created_at->format('H:i | d.m') }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($appeal->status == 'new')
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold uppercase border border-emerald-200">Yangi</span>
                            @elseif($appeal->status == 'processing')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold uppercase border border-yellow-200">Jarayonda</span>
                            @elseif($appeal->status == 'closed')
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase border border-slate-200">Yopilgan</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase border border-red-200">Rad etilgan</span>
                            @endif
                        </td>

                        <!-- Amal -->
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.show', $appeal->id) }}" class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-white border border-slate-200 text-slate-400 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition shadow-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                                <p class="text-sm font-medium">Ma'lumot topilmadi</p>
                                <p class="text-xs">Qidiruv so'zini o'zgartirib ko'ring yoki filterlarni tozalang</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Sahifalash -->
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $appeals->appends(request()->query())->links() }}
        </div>
    </div>
@endsection