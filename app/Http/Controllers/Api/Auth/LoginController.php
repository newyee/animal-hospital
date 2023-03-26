<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\User\AuthorisationResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class LoginController extends Controller
{

    public function handleLogin(LoginRequest $request): JsonResponse
    {
        // バリデーション
        $credentials = $request->validated();
        // DBと照合
        $token = Auth::attempt($credentials);
        // アンダーバー二つはローカリゼーションファイルを参照する関数
        // https://readouble.com/laravel/8.x/ja/helpers.html#method-__
        if (! $token) {
            return response()->json([
                'message' => 'Unauthorized',
                'errors' => ['email' => __('auth.failed')],
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        return response()->json([
            'user' => new UserResource($user),
            'authorisation' => new AuthorisationResource(compact('token')),
        ]);
    }
}
