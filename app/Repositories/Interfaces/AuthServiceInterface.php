<?php

namespace App\Repositories\Interfaces;

interface AuthServiceInterface{
    public function login(array $credentials);
    public function logout();
    public function refresh();

}