<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MyPage\PasswordChangeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function updatePassword(PasswordChangeRequest $request)
    {
        $user = Auth::guard()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => __('passwords.change')]);
    }
}
