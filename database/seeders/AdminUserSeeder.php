<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@angele.local'],
            [
                'name' => 'Admin',
                'password' => 'admin123',
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@angele.local'],
            [
                'name' => 'User',
                'password' => 'user123',
                'role' => User::ROLE_USER,
            ]
        );

        // Compatibilité ancien compte marina
        User::updateOrCreate(
            ['email' => 'marina@admin.local'],
            [
                'name' => 'marina',
                'password' => config('marina.admin_password', 'marina4321'),
                'role' => User::ROLE_ADMIN,
            ]
        );
    }
}
