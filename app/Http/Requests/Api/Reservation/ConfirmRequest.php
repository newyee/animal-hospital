<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Reservation;

use App\Http\Requests\FailedValidationTrait;
use App\Rules\AlphaHyphenRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PhoneNumberValidationRule;

final class ConfirmRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'owner_name' => [
                'required',
                'string',
            ],
            'ticket_number' => [
                'required',
                'string',
                new AlphaHyphenRule
            ],
            'email' => [
                'required',
                'string',
                'email',
            ],
            'phone_number' => [
                'required',
                new PhoneNumberValidationRule
            ],
            'remark' => [
                'required',
                'string',
            ],
            'pet_name' => [
                'required',
                'string',
            ],
            'pet_type' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     *  バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'owner_name' => '飼い主名',
            'email' => 'メールアドレス',
            'phone_number' => '電話番号',
            'remark' => '備考欄',
            'pet_name' => 'ペット名',
            'pet_type' => 'ペットの種類'
        ];
    }
}
