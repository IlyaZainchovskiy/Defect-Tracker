<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Аналітика та звіти') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Фільтри</h3>
                    
                    <form action="{{ route('analytics.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="date_from" class="block mb-1">Дата від:</label>
                            <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block mb-1">Дата до:</label>
                            <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
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
                        
                        <div class="md:col-span-4 flex justify-between">
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Застосувати фільтри</button>
                            
                            @if(auth()->user()->isAdmin())
                                <div class="flex space-x-2">
                                    <a href="{{ route('analytics.export-pdf') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&department_id={{ request('department_id') }}&reason_id={{ request('reason_id') }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        Експорт PDF
                                    </a>
                                    
                                    <a href="{{ route('analytics.export-excel') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&department_id={{ request('department_id') }}&reason_id={{ request('reason_id') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Експорт Excel
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Графік кількості браку по місяцях -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Кількість браку по місяцях</h3>
                        
                        @if(count($monthlyDefects) > 0)
                            <div class="h-64">
                                <canvas id="monthlyDefectsChart"></canvas>
                            </div>
                        @else
                            <p>Немає даних для відображення графіка.</p>
                        @endif
                    </div>
                </div>
                
                <!-- Найпоширеніші причини браку -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Найпоширеніші причини браку</h3>
                        
                        @if(count($topReasons) > 0)
                            <div class="h-64">
                                <canvas id="topReasonsChart"></canvas>
                            </div>
                        @else
                            <p>Немає даних для відображення графіка.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Розподіл по підрозділах -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Розподіл по підрозділах</h3>
                    
                    @if(count($departmentStats) > 0)
                        <div class="h-80">
                            <canvas id="departmentsChart"></canvas>
                        </div>
                    @else
                        <p>Немає даних для відображення графіка.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if(count($monthlyDefects) > 0 || count($topReasons) > 0 || count($departmentStats) > 0)
        <!--  Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDarkMode = document.querySelector('html').classList.contains('dark');
                const textColor = isDarkMode ? '#f3f4f6' : '#374151';
                const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                
                // Графік кількості браку по місяцях
                @if(count($monthlyDefects) > 0)
                    const monthlyDefectsCtx = document.getElementById('monthlyDefectsChart').getContext('2d');
                    new Chart(monthlyDefectsCtx, {
                        type: 'line',
                        data: {
                            labels: @json($monthlyDefects->pluck('month')),
                            datasets: [{
                                label: 'Кількість випадків',
                                data: @json($monthlyDefects->pluck('count')),
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 2,
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: textColor
                                    }
                                }
                            }
                        }
                    });
                @endif
                
                // Графік найпоширеніших причин браку
                @if(count($topReasons) > 0)
                    const topReasonsCtx = document.getElementById('topReasonsChart').getContext('2d');
                    new Chart(topReasonsCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($topReasons->pluck('name')),
                            datasets: [{
                                label: 'Кількість випадків',
                                data: @json($topReasons->pluck('defects_count')),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 206, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: textColor
                                    }
                                }
                            }
                        }
                    });
                @endif
                
                // Графік розподілу по підрозділах
                @if(count($departmentStats) > 0)
                    const departmentsCtx = document.getElementById('departmentsChart').getContext('2d');
                    new Chart(departmentsCtx, {
                        type: 'pie',
                        data: {
                            labels: @json($departmentStats->pluck('name')),
                            datasets: [{
                                data: @json($departmentStats->pluck('defects_count')),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 206, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)',
                                    'rgba(255, 159, 64, 0.6)',
                                    'rgba(199, 199, 199, 0.6)',
                                    'rgba(83, 102, 255, 0.6)',
                                    'rgba(78, 205, 196, 0.6)',
                                    'rgba(232, 65, 24, 0.6)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(199, 199, 199, 1)',
                                    'rgba(83, 102, 255, 1)',
                                    'rgba(78, 205, 196, 1)',
                                    'rgba(232, 65, 24, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        color: textColor
                                    }
                                }
                            }
                        }
                    });
                @endif
            });
        </script>
    @endif
</x-app-layout>