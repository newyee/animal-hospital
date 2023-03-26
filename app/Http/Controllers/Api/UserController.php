<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MyPage\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function update(UserRequest $request): JsonResponse
    {
        $user = Auth::guard()->user();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->save();
        return response()->json(['name' => $user->name, 'phoneNumber' => $user->phone_number]);
    }
    public function delete(Request $request): JsonResponse
    {
        $user = Auth::guard()->user();
        $user->delete();
        return response()->json();
    }
}
