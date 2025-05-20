<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Причини браку') }}
            </h2>
            <a href="{{ route('reasons.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                {{ __('Додати причину') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('reasons.index') }}" method="GET" class="flex">
                        <x-text-input class="block w-full mr-2" type="text" name="search" :value="request('search')" placeholder="Пошук за назвою причини..." />
                        <x-primary-button>
                            {{ __('Пошук') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Список причин браку</h3>
                    
                    @if(count($reasons) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Назва</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Опис</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">К-сть випадків</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Дії</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reasons as $reason)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $reason->id }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $reason->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $reason->description }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $reason->defects_count ?? $reason->defects->count() }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('reasons.edit', $reason) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Редагувати</a>
                                                    
                                                    <form action="{{ route('reasons.destroy', $reason) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цю причину?');">
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
                            {{ $reasons->links() }}
                        </div>
                    @else
                        <p>Немає записів про причини браку.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>