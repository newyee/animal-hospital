<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class MeController extends Controller
{
    public function handle(): ?UserResource
    {
        /** @var User $user */
        $user = Auth::guard()->user();
        return ! ! $user ? new UserResource($user) : null;
    }
}
