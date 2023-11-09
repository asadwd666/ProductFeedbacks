<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\User;
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
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Adm!n@143'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // user
        $user = User::create([
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
       
        $posting=Permission::create(['name' => 'posting']);
        $commenting=Permission::create(['name' => 'commenting']);
        $user->roles()->attach($userRole);
        $user->permissions()->attach([$posting->id, $commenting->id]);
        $adminUser->roles()->attach($adminRole);
        //adding default post for user
        Feedback::create([
            'title'=>'He this is Default post',
            'description'=>'<p>User can comment on this but that user should have login ist</p>',
            'category'=>'improvement',
            'user_id'=>1,
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

    }
}
