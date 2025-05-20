<!-- resources/views/defects/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Додати новий випадок браку') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('defects.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="date" :value="__('Дата')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="department_id" :value="__('Виробнича дільниця')" />
                                <select id="department_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="department_id" required>
                                    <option value="">Оберіть підрозділ</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                                                            @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="product_type" :value="__('Тип продукції')" />
                                <x-text-input id="product_type" class="block mt-1 w-full" type="text" name="product_type" :value="old('product_type')" required />
                                <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="reason_id" :value="__('Причина браку')" />
                                <select id="reason_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="reason_id" required>
                                    <option value="">Оберіть причину</option>
                                    @foreach($reasons as $reason)
                                        <option value="{{ $reason->id }}" {{ old('reason_id') == $reason->id ? 'selected' : '' }}>
                                            {{ $reason->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('reason_id')" class="mt-2" />
                            </div>
                            
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="description" :value="__('Опис проблеми')" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('defects.index') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 mr-2">
                                Скасувати
                            </a>
                            
                            <x-primary-button>
                                {{ __('Зберегти') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>