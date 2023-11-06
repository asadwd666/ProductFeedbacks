<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Adm!n@143'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // user
        DB::table('users')->insert([
            'name' => 'Khan',
            'email' => 'khan@example.com',
            'password' => Hash::make('User@123'),
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $adminRole = Role::create(['name' => 'admin']);
        $moderatorRole = Role::create(['name' => 'moderator']);
        $userRole = Role::create(['name' => 'user']);

        // Permissions
        $manageUsersPermission = Permission::create(['name' => 'manage users']);
        $manageCommentsPermission = Permission::create(['name' => 'manage comments']);
        $posting=Permission::create(['name' => 'posting']);
        $commenting=Permission::create(['name' => 'commenting']);
        


        // Assign permissions to roles
        $adminRole->givePermissionTo($manageUsersPermission, $manageCommentsPermission);
        $moderatorRole->givePermissionTo($manageCommentsPermission);
    }
}
