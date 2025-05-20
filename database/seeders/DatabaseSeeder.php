<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Reason;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Адміністратор',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        User::create([
            'name' => 'Користувач',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $departments = [
            ['name' => 'Виробництво', 'description' => 'Основне виробництво'],
            ['name' => 'Упаковка', 'description' => 'Пакування готової продукції'],
            ['name' => 'Контроль якості', 'description' => 'Перевірка якості продукції'],
            ['name' => 'Логістика', 'description' => 'Транспортування продукції'],
        ];
        
        foreach ($departments as $department) {
            Department::create($department);
        }
        
        $reasons = [
            ['name' => 'Несправність обладнання', 'description' => 'Поломка або несправність обладнання'],
            ['name' => 'Помилка оператора', 'description' => 'Неправильні дії персоналу'],
            ['name' => 'Низька якість сировини', 'description' => 'Використання неякісної сировини'],
            ['name' => 'Порушення технології', 'description' => 'Недотримання технологічного процесу'],
            ['name' => 'Непередбачені обставини', 'description' => 'Форс-мажорні ситуації'],
        ];
        
        foreach ($reasons as $reason) {
            Reason::create($reason);
        }
    }
}
