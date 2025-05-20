<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Журнал браку') }}
            </h2>
            <a href="{{ route('defects.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                {{ __('Додати випадок') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Фільтри</h3>
                    
                    <form action="{{ route('defects.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="date_from" class="block mb-1">Дата від:</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block mb-1">Дата до:</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        </div>
                        
                        <div>
                            <label for="department_id" class="block mb-1">Підрозділ:</label>
                            <select id="department_id" name="department_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="">Всі підрозділи</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="reason_id" class="block mb-1">Причина:</label>
                            <select id="reason_id" name="reason_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="">Всі причини</option>
                                @foreach($reasons as $reason)
                                    <option value="{{ $reason->id }}" {{ request('reason_id') == $reason->id ? 'selected' : '' }}>
                                        {{ $reason->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Застосувати фільтри</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Список випадків браку</h3>
                    
                    @if(count($defects) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Дата</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Підрозділ</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Тип продукції</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Причина</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Відповідальний</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Дії</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($defects as $defect)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $defect->id }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $defect->date->format('d.m.Y') }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $defect->department->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $defect->product_type }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $defect->reason->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $defect->user->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('defects.show', $defect) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Деталі</a>
                                                    
                                                    @can('update', $defect)
                                                        <a href="{{ route('defects.edit', $defect) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Редагувати</a>
                                                    @endcan
                                                    
                                                    @can('delete', $defect)
                                                        <form action="{{ route('defects.destroy', $defect) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цей запис?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Видалити</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $defects->links() }}
                        </div>
                    @else
                        <p>Немає записів про випадки браку.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>