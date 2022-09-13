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
            'name' => 'create-user'
        ]);

        Permission::firstOrCreate([
            'name' => 'viewany-user'
        ]);

        Permission::firstOrCreate([
            'name' => 'view-user'
        ]);

        Permission::firstOrCreate([
            'name' => 'update-user'
        ]);

        Permission::firstOrCreate([
            'name' => 'delete-user'
        ]);

        //NOTES
        Permission::firstOrCreate([
            'name' => 'create-note'
        ]);

        Permission::firstOrCreate([
            'name' => 'viewany-note'
        ]);

        Permission::firstOrCreate([
            'name' => 'view-note'
        ]);

        Permission::firstOrCreate([
            'name' => 'update-note'
        ]);

        Permission::firstOrCreate([
            'name' => 'delete-note'
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
        ]);

        $user = Role::where('name', 'user')->first();
        $user->givePermissionTo([
            'create-note',
            'viewany-note',
            'view-note',
            'update-note',
            'delete-note',
        ]);

    }
}
