<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Mail\PasswordResetLink;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    //
    public function sendPasswordResetLink(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // ユーザーの登録情報を漏らさないようにする為、正常系として200を返す
        if (!$user) {
            return response()->json([], 200);
        }

        $token = Str::random(60);
        $expiresAt = now()->addMinutes(30);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        $emailContent = new PasswordResetLink($user->name, $token, $expiresAt);

        Mail::to($email)->send($emailContent);

        return response()->json([], 200);
    }

    public function verifyPasswordToken(Request $request): JsonResponse
    {
        $token = $request->input('token');
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();

        if (!$passwordReset || $passwordReset->expires_at < now()) {
            return response()->json(['error' => __('passwords.expired_or_invalid')], JsonResponse::HTTP_BAD_REQUEST);
        }
        $email = $passwordReset->email;
        return response()->json(['email' => $email], 200);
    }
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $user = User::where('email', $email)->first();
            $user->password = Hash::make($password);
            $user->save();
            DB::table('password_resets')->where('email', $email)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => __('passwords.reset_password_error')], JsonResponse::HTTP_BAD_REQUEST);
        }
        return response()->json(['message' => __('passwords.reset_password_success')], 200);
    }
}
