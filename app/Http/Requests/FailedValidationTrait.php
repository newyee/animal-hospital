<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

trait FailedValidationTrait
{
    protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors()->keys())
            ->mapWithKeys(fn ($key) => [Str::camel($key) => $validator->errors()->get($key)[0] ?? ''])
            ->toArray();
        $res = response()->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($res);
    }
}
