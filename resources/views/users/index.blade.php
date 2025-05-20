<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Користувачі') }}
            </h2>
            <a href="{{ route('users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                {{ __('Додати користувача') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Список користувачів</h3>
                    
                    @if(count($users) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Ім'я</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Підрозділ</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Роль</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Статус</th>
                                        <th class="px-6 py-3 border-b border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Дії</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $user->id }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $user->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $user->email }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">{{ $user->department?->name ?? 'Не призначено' }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                    {{ $user->role === 'admin' ? 'Адміністратор' : 'Користувач' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                                    {{ $user->is_active ? 'Активний' : 'Неактивний' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-300 dark:border-gray-700">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Редагувати</a>
                                                    
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="{{ $user->is_active ? 'text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300' : 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' }}">
                                                                {{ $user->is_active ? 'Деактивувати' : 'Активувати' }}
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Ви впевнені, що хочете видалити цього користувача?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Видалити</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <p>Немає зареєстрованих користувачів.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>