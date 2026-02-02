@extends('layouts.admin')

@section('title', 'Kategoriyalar')

@section('content')
    <div class="flex gap-6">
        <!-- Add Form -->
        <div class="w-1/3">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-lg mb-4">Yangi Kategoriya Qo'shish</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nomi</label>
                        <input type="text" name="name" placeholder="Masalan: Pora talab qilish" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <button class="w-full py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700">SAQLASH</button>
                </form>
            </div>
        </div>

        <!-- List -->
        <div class="w-2/3">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Nomi</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Amal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories as $category)
                        <tr>
                            <td class="px-6 py-3 text-sm text-gray-500">#{{ $category->id }}</td>
                            <td class="px-6 py-3 font-bold text-gray-800">{{ $category->name }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">Aktiv</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection