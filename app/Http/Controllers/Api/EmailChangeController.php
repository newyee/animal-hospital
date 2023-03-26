<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MyPage\EmailChangeRequest;
use App\Mail\EmailChangeVerification;
use App\Models\EmailChange;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class EmailChangeController extends Controller
{
    public function updateEmail(EmailChangeRequest $request): JsonResponse
    {

        $user = Auth::guard()->user();
        // 既に存在するメールアドレスの場合はエラーを返す
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['errors' => ['email' => __('mail.email_exists')]], JsonResponse::HTTP_BAD_REQUEST);
        }
        // トークンを生成する
        $token = Str::uuid();
        $expiresAt = now()->addDay();
        // email_changesテーブルにレコードを追加する
        DB::table('email_changes')->insert([
            'user_id' => $request->user_id,
            'new_email' => $request->email,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => $expiresAt,
        ]);
        $emailChange = new EmailChangeVerification($user->name, $token, $expiresAt);
        Mail::to($request->email)->send($emailChange);
        return response()->json(['newEmail' => $request->email, 'message' => __('mail.confirmation_sent_message')]);
    }

    public function verifyEmail(Request $request): JsonResponse
    {

        $token = $request->code;

        // email_changesテーブルからトークンが一致するレコードを取得する
        $emailChange = EmailChange::where('token', $token)->first();
        if (!$emailChange) {
            return response()->json(['error' => __('mail.mail_activation_error')], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($emailChange->expires_at < now()) {
            return response()->json(['error' => __('mail.mail_expired')], JsonResponse::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            // usersテーブルのemailカラムを更新する
            $user = User::where('id', $emailChange->user_id)->first();
            $user->email = $emailChange->new_email;
            $user->save();
            // email_changesテーブルからレコードを削除する
            $emailChange->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['error' => __('mail.update_email_failed')], 500);
        }
        return response()->json(['email' => $emailChange->new_email, 'message' => __('mail.new_email_verified_message')], 200);
    }

    public function resendEmail(Request $request): JsonResponse
    {
        $user = Auth::guard()->user();

        // トークンを生成する
        $token = Str::uuid();
        $expiresAt = now()->addDay();

        // email_changesテーブルにレコードを追加する
        $emailChange = EmailChange::where('user_id', $user->id)->first();
        if (!$emailChange) {
            return response()->json(['error' => 'No email change request found'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $emailChange->timestamps = false;
        $emailChange->update([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
        $email = new EmailChangeVerification($user->name, $token, $expiresAt);
        Mail::to($emailChange->new_email)->send($email);
        return response()->json(['message' => __('mail.resend_confirmation_message')]);
    }

    public function cancelChangeEmail(Request $request): JsonResponse
    {
        $user = Auth::guard()->user();
        // email_changesテーブルから対象のレコードを取得する
        $emailChangeRecord = DB::table('email_changes')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        // レコードが存在しない場合はエラーを返す
        if (!$emailChangeRecord) {
            return response()->json(['errors' => ['email' => __('mail.no_mail_address_change')]], JsonResponse::HTTP_BAD_REQUEST);
        }

        // email_changesテーブルからレコードを削除する
        DB::table('email_changes')
            ->where('id', $emailChangeRecord->id)
            ->delete();

        return response()->json(['message' => __('mail.mail_address_change_cancelled')]);
    }
}
