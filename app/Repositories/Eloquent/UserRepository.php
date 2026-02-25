<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {
    public function findById(int $id) {
        return User::with('roles')->findOrFail($id);
    }

    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }
}