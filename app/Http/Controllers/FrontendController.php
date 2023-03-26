<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final class FrontendController extends Controller
{
    public function app(): View
    {
        return view('app');
    }
}
