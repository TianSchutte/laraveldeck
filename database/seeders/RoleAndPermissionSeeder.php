<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'create-roles']);
        Permission::create(['name' => 'edit-roles']);
        Permission::create(['name' => 'delete-roles']);


        $adminRole = Role::create(['name' => 'Admin']);
        $moderatorRole = Role::create(['name' => 'Moderator']);
        $userRole = Role::create(['name' => 'User']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'create-roles',
            'edit-roles',
            'delete-roles',
        ]);

        $moderatorRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users'
        ]);
    }
}
