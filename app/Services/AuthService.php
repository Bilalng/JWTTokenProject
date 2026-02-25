<?php

namespace App\Services;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials)
    {
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return null;
        }

        return $this->formatToken($token);
    }

    protected function formatToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTl() * 60,
            'user' => auth('api')->user()
        ];
    }

}