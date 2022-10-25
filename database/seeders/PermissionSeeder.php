<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //USERS
        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'create-user'
        ]);

        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'viewany-user'
        ]);

        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'view-user'
        ]);

        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'update-user'
        ]);

        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'delete-user'
        ]);

        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'restore-user'
        ]);

        Permission::firstOrCreate([
            'group' => 'user',
            'name' => 'forcedelete-user'
        ]);

        //NOTES
        Permission::firstOrCreate([
            'group' => 'note',
            'name' => 'create-note'
        ]);

        Permission::firstOrCreate([
            'group' => 'note',
            'name' => 'viewany-note'
        ]);

        Permission::firstOrCreate([
            'group' => 'note',
            'name' => 'view-note'
        ]);

        Permission::firstOrCreate([
            'group' => 'note',
            'name' => 'update-note'
        ]);

        Permission::firstOrCreate([
            'group' => 'note',
            'name' => 'delete-note'
        ]);

        //ROLES
        Permission::firstOrCreate([
            'group' => 'role',
            'name' => 'create-role'
        ]);

        Permission::firstOrCreate([
            'group' => 'role',
            'name' => 'viewany-role'
        ]);

        Permission::firstOrCreate([
            'group' => 'role',
            'name' => 'view-role'
        ]);

        Permission::firstOrCreate([
            'group' => 'role',
            'name' => 'update-role'
        ]);

        Permission::firstOrCreate([
            'group' => 'role',
            'name' => 'delete-role'
        ]);


        $admin = Role::where('name', 'admin')->first();
        $admin->givePermissionTo([
            'create-user',
            'viewany-user',
            'view-user',
            'update-user',
            'delete-user',
            'create-note',
            'view-note',
            'viewany-note',
            'update-note',
            'delete-note',
            'create-role',
            'view-role',
            'viewany-role',
            'update-role',
            'delete-role',
        ]);

        $user = Role::where('name', 'user')->first();
        $user->givePermissionTo([
            'create-note',
            'viewany-note',
            'view-note',
            'update-note',
            'delete-note',
        ]);

        $testrole = Role::where('name', 'testrole')->first();
        $testrole->givePermissionTo([
            'viewany-note',
            'view-note',
        ]);

    }
}
