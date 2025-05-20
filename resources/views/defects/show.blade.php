<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Деталі випадку браку #') }}{{ $defect->id }}
            </h2>
            <div>
                <a href="{{ route('defects.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 mr-2">
                    Назад до списку
                </a>
                
                @can('update', $defect)
                    <a href="{{ route('defects.edit', $defect) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 mr-2">
                        Редагувати
                    </a>
                @endcan
                
                @can('delete', $defect)
                    <form action="{{ route('defects.destroy', $defect) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цей запис?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Видалити
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Загальна інформація</h3>
                            <div class="border border-gray-300 dark:border-gray-700 rounded-md p-4">
                                <div class="mb-3">
                                    <span class="font-medium">Дата:</span>
                                    <span>{{ $defect->date->format('d.m.Y') }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="font-medium">Тип продукції:</span>
                                    <span>{{ $defect->product_type }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="font-medium">Підрозділ:</span>
                                    <span>{{ $defect->department->name }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium">Причина браку:</span>
                                    <span>{{ $defect->reason->name }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Відповідальна особа</h3>
                            <div class="border border-gray-300 dark:border-gray-700 rounded-md p-4">
                                <div class="mb-3">
                                    <span class="font-medium">Ім'я:</span>
                                    <span>{{ $defect->user->name }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium">Email:</span>
                                    <span>{{ $defect->user->email }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-semibold mb-2">Опис проблеми</h3>
                            <div class="border border-gray-300 dark:border-gray-700 rounded-md p-4">
                                <p>{{ $defect->description }}</p>
                            </div>
                        </div>
                        
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-lg font-semibold mb-2">Інформація про запис</h3>
                            <div class="border border-gray-300 dark:border-gray-700 rounded-md p-4">
                                <div class="mb-3">
                                    <span class="font-medium">Створено:</span>
                                    <span>{{ $defect->created_at->format('d.m.Y H:i:s') }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium">Оновлено:</span>
                                    <span>{{ $defect->updated_at->format('d.m.Y H:i:s') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>