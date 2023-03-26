<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Reservation;

use App\Http\Requests\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class CancelRequest extends FormRequest
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
            //
            'cancellation_reason' => [
                'required',
            ],
        ];
    }
    public function messages()
    {
        return [
            'cancellation_reason' => 'キャンセル理由',
        ];
    }
}
