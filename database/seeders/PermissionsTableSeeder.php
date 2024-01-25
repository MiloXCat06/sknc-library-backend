<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //permission for books
        Permission::create(['name' => 'books.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'books.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'books.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'books.delete', 'guard_name' => 'api']);

        // //permission for borrows
        // Permission::create(['name' => 'borrows.index', 'guard_name' => 'api']);
        // Permission::create(['name' => 'borrows.create', 'guard_name' => 'api']);
        // Permission::create(['name' => 'borrows.edit', 'guard_name' => 'api']);
        // Permission::create(['name' => 'borrows.delete', 'guard_name' => 'api']);

        // //permission for restore
        // Permission::create(['name' => 'sliders.index', 'guard_name' => 'api']);
        // Permission::create(['name' => 'sliders.create', 'guard_name' => 'api']);
        // Permission::create(['name' => 'sliders.edit', 'guard_name' => 'api']);
        
        //permission for roles
        Permission::create(['name' => 'roles.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'api']);

        //permission for permissions
        Permission::create(['name' => 'permissions.index', 'guard_name' => 'api']);

        //permission for users
        Permission::create(['name' => 'users.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'users.delete', 'guard_name' => 'api']);
    }
}
