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
    // İzinler
    $listPerm = Permission::updateOrCreate(['slug' => 'user-list'], ['name' => 'Listeleme']);
    $deletePerm = Permission::updateOrCreate(['slug' => 'user-delete'], ['name' => 'Silme']);

    $adminRole = Role::updateOrCreate(['slug' => 'admin'], ['name' => 'Yönetici']);
    $editorRole = Role::updateOrCreate(['slug' => 'editor'], ['name' => 'Yazar']);

    $adminRole->permissions()->sync([$listPerm->id, $deletePerm->id]); 
    $editorRole->permissions()->sync([$listPerm->id]); 

    $admin = User::updateOrCreate(['email' => 'admin@test.com'], [
        'name' => 'Admin Kanka',
        'password' => Hash::make('password123'),
    ]);
    $admin->roles()->sync([$adminRole->id]);

    $editor = User::updateOrCreate(['email' => 'yazar@test.com'], [
        'name' => 'Yazar Kanka',
        'password' => Hash::make('password123'),
    ]);
    $editor->roles()->sync([$editorRole->id]);
}
}