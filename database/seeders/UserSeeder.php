<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin'),
                'remember_token' => null,
            ],
            [
                'name' => 'Agent',
                'email' => 'agent@mail.com',
                'password' => bcrypt('agent'),
                'remember_token' => null,
            ],
        ];

        User::query()->insert($users);
    }
}
