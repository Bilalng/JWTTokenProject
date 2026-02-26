<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $userList = Permission::create(['name' => 'Kullanıcıları Listele', 'slug' => 'user-list']);
        $userDelete = Permission::create(['name' => 'Kullanıcı Sil', 'slug' => 'user-delete']);

        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $employeeRole = Role::create(['name' => 'Çalışan', 'slug' => 'employee']);

        $adminRole->permissions()->attach([$userList->id, $userDelete->id]);
        $employeeRole->permissions()->attach([$userList->id]);

        $admin = User::create([
            'name' => 'Admin Kanka',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        $admin->roles()->attach($adminRole->id);
    }
}