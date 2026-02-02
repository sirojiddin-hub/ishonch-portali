@extends('layouts.admin')

@section('title', 'Arxiv')

@section('content')
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm min-h-[400px]">
        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 text-lg">
            <i class="fas fa-history text-slate-400"></i> Arxivlangan Ishlar
        </h3>

        @if(count($years) > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($years as $year)
                <a href="{{ route('admin.archive.show', $year) }}" class="group block text-center">
                    <div class="bg-slate-50 rounded-2xl p-6 border-2 border-slate-100 group-hover:border-blue-200 group-hover:bg-blue-50 transition-all mb-3 relative overflow-hidden">
                        <i class="fas fa-folder text-5xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                        
                        <!-- Papka effekti -->
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                    </div>
                    <span class="font-bold text-slate-600 group-hover:text-blue-600 text-lg transition-colors">{{ $year }}</span>
                    <p class="text-[10px] text-slate-400 uppercase font-bold mt-1">Yillik Hisobot</p>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <i class="fas fa-box-open text-6xl text-slate-200 mb-4"></i>
                <p class="text-slate-400">Arxivda hozircha ma'lumot yo'q</p>
            </div>
        @endif
    </div>
@endsection