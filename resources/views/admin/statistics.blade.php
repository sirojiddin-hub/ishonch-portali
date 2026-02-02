@extends('layouts.admin')

@section('title', 'Tahlil va Hisobot')

@section('content')
    <!-- Yuqori qism: Qisqacha vidjetlar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] text-slate-400 font-bold uppercase">Jami Arizalar</span>
            <div class="text-2xl font-bold text-slate-800 mt-1">{{ $total }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
            <span class="text-[10px] text-emerald-600 font-bold uppercase">Yangi</span>
            <div class="text-2xl font-bold text-slate-800 mt-1">{{ $new }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-yellow-400">
            <span class="text-[10px] text-yellow-600 font-bold uppercase">Jarayonda</span>
            <div class="text-2xl font-bold text-slate-800 mt-1">{{ $processing }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-slate-500">
            <span class="text-[10px] text-slate-500 font-bold uppercase">Yopilgan</span>
            <div class="text-2xl font-bold text-slate-800 mt-1">{{ $closed }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- 1. Eng ko'p uchraydigan muammolar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-full">
            <h3 class="font-bold text-slate-800 mb-6">Eng ko'p uchraydigan muammolar</h3>
            
            <div class="space-y-6">
                @foreach($topProblems as $problem)
                    @php
                        // Foizni hisoblash
                        $percent = $total > 0 ? round(($problem->total / $total) * 100) : 0;
                        
                        // Ranglarni almashtirish uchun mantiq
                        $colors = ['bg-red-500', 'bg-orange-500', 'bg-blue-500', 'bg-indigo-500', 'bg-slate-400'];
                        $color = $colors[$loop->index % 5];
                    @endphp
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-sm font-bold text-slate-700">{{ $problem->type }}</span>
                            <span class="text-xs font-mono font-bold text-slate-500">{{ $percent }}% ({{ $problem->total }})</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach

                @if($topProblems->isEmpty())
                    <p class="text-center text-slate-400 text-sm py-10">Ma'lumotlar yetarli emas</p>
                @endif
            </div>
        </div>

        <!-- 2. Hududiy Taqsimot (Grafik) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-full flex flex-col">
            <h3 class="font-bold text-slate-800 mb-6">Hududiy taqsimot</h3>
            
            <div class="flex-1 min-h-[300px] relative">
                <canvas id="regionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Kutubxonasi -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('regionChart');

        // Controllerdan kelgan ma'lumotlar
        const labels = @json($chartLabels);
        const data = @json($chartData);

        if (labels.length > 0) {
            new Chart(ctx, {
                type: 'bar', // Grafik turi (bar, pie, line)
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Arizalar soni',
                        data: data,
                        backgroundColor: [
                            '#10B981', // Emerald
                            '#3B82F6', // Blue
                            '#F59E0B', // Amber
                            '#6366F1', // Indigo
                            '#EC4899', // Pink
                            '#64748B'  // Slate
                        ],
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false } // Legendani o'chirish
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        } else {
            document.getElementById('regionChart').parentNode.innerHTML = 
                '<div class="flex items-center justify-center h-full text-slate-400 text-sm">Grafik uchun ma\'lumot yo\'q</div>';
        }
    </script>
@endsection