<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_permissions = Permission::all();

        $agent_permissions = Permission::query()
            ->whereIn('name', [
                'category_access',
                'category_show',
                'ticket_access',
                'ticket_show',
            ])->get();

        Role::query()->findOrFail(1)->permissions()->sync($admin_permissions);
        Role::query()->findOrFail(2)->permissions()->sync($agent_permissions);
    }
}
