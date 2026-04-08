<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@todo-list.local'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'password' => Hash::make('Admin123!'),
                'role' => UserRole::ADMIN,
                'is_active' => true,
                'locale' => 'pl',
                'email_verified_at' => now(),
            ]
        );
    }
}
