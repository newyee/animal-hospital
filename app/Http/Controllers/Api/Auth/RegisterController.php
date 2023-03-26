<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Mail\ActivationCreated;
use App\Models\Activation;
use App\Models\ConsultationReservation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class RegisterController extends Controller
{

    /**
     * 登録リクエストを受付
     *
     * @param App\Http\Requests\Api\Auth\RegisterRequest
     * @return void
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->createActivation($request);

        return response()->json([
            'requestMail' => $request->email
        ]);
    }

    /**
     * アクティベーションコードを生成して認証コードをメールで送信
     *
     * @param
     * @return void
     */
    private function createActivation(RegisterRequest $request): void
    {
        $activation = new Activation;
        $activation->user_name = $request->name;
        $activation->email = $request->email;
        $activation->password = Hash::make($request->password);
        $activation->code = Str::uuid();
        $activation->save();
        Mail::to($activation->email)->send(new ActivationCreated($activation));
    }

    /**
     * メール認証コードを検証してユーザ情報の登録
     *
     * @param Request
     * @return string
     */
    public function verify(Request $request): JsonResponse
    {
        $code = $request->code;
        // 認証確認
        if (! $this->checkCode($code)) {
            return response()->json([
                'message' => __('mail.mail_activation_error')
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        // ユーザ情報の登録
        return DB::transaction(function () use ($code) {
            $activation = Activation::where('code', $code)->first();
            $user = new User();
            $user->name = $activation->user_name;
            $user->email = $activation->email;
            $user->password = $activation->password;
            $user->save();
            Activation::where('code', $code)->update(['email_verified_at' => Carbon::now()]);
            $userReservation = ConsultationReservation::where('mail_address', $activation->email);
            if ($userReservation) {
                $userReservation->update(['user_id' => $user->id]);
            }
            return response()->json([], JsonResponse::HTTP_OK);
        });
    }

    /**
     *  メール認証コードの検証
     *
     *  1. 与えられた認証コードがActivations.codeに存在するか?
     *  2. users.emailが存在しユーザ登録が既に完了したメールアドレスかどうか?
     *  3. 認証コード発行後1日以内に発行された認証コードであるか?
     *
     * @param string $code - メール認証のURLパラメータから取得する認証コード
     * @return boolean
     */
    private function checkCode(string $code): bool
    {
        $activation = Activation::where('code', $code)->first();
        if (! $activation) {
            return false;
        }

        $latest = Activation::where('email', $activation->email)->latest()->first();
        $user = User::where('email', $activation->email)->first();
        return $code === $latest->code && ! $user && Carbon::now()->lt($activation->expireAt());
    }
}
