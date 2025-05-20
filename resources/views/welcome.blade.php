<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Головна') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Статистика браку -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Загальна статистика браку</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="text-blue-800 dark:text-blue-200 font-medium">Всього випадків браку за останній місяць</h4>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-300">{{ $lastMonthDefects }}</p>
                        </div>
                        
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                            <h4 class="text-green-800 dark:text-green-200 font-medium">Додати новий випадок</h4>
                            <a href="{{ route('defects.create') }}" class="mt-2 inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Додати</a>
                        </div>
                        
                        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                            <h4 class="text-purple-800 dark:text-purple-200 font-medium">Переглянути аналітику</h4>
                            <a href="{{ route('analytics.index') }}" class="mt-2 inline-block px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">Аналітика</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Найпоширеніші причини браку -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Найпоширеніші причини браку</h3>
                    
                    @if(count($topReasons) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Причина</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Кількість випадків</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topReasons as $reason)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $reason->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $reason->defects_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Немає даних про причини браку.</p>
                    @endif
                </div>
            </div>
            
            <!-- Розподіл по підрозділах -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Розподіл по підрозділах</h3>
                    
                    @if(count($departmentStats) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Підрозділ</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Кількість випадків</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departmentStats as $department)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->defects_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Немає даних про розподіл браку по підрозділах.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>