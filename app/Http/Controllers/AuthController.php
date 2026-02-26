<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result) {
            return response()->json(['error' => 'Kimlik bilgileri hatalı veya kullanıcı bulunamadı.'], 401);
        }

        return response()->json([
            'message' => 'Giriş başarılı',
            'data' => $result
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Başarıyla çıkış yapıldı']);
    }

   
    public function refresh(): JsonResponse
    {
        $result = $this->authService->refresh();

        return response()->json([
            'message' => 'Token yenilendi',
            'data' => $result
        ]);
    }
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }
}
