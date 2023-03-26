<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class LogoutController extends Controller
{
    public function handle(): JsonResponse
    {
        Auth::guard()->logout();
        // オブジェクトへのキャスト
        return response()->json((object) []);
    }
}
