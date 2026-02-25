<?php

namespace App\Services;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
class AuthService implements AuthServiceInterface
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
        $user = auth('api')->user();
        $user->load('roles');
        return $this->formatToken($token, $user);
    }

    protected function formatToken($token, $user)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTl() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('slug')
            ]
        ];
    }

    public function logout(){
         Auth::guard('api')->logout();
        return true;
    }

    public function refresh(){
        $newtoken = Auth::guard('api')->refresh();

        return $this->formatToken($newtoken, auth('api')->user());
    }
}