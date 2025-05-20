<!-- resources/views/departments/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Підрозділи') }}
            </h2>
            <a href="{{ route('departments.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                {{ __('Додати підрозділ') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Список підрозділів</h3>
                    
                    @if(count($departments) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Назва</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Опис</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">К-сть працівників</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">К-сть випадків</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Дії</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $department)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->id }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->description }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->users_count ?? $department->users->count() }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $department->defects_count ?? $department->defects->count() }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                                                <div class="flex space-x-2">
                                                                                                        <a href="{{ route('departments.edit', $department) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Редагувати</a>
                                                    
                                                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цей підрозділ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Видалити</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $departments->links() }}
                        </div>
                    @else
                        <p>Немає записів про підрозділи.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>