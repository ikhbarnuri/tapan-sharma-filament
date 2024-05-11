<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->findOrFail(1)->roles()->sync(1);
        User::query()->findOrFail(2)->roles()->sync(2);
    }
}
