<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // データのラップを無効する
        // https://readouble.com/laravel/8.x/ja/eloquent-resources.html
        JsonResource::withoutWrapping();
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }
}
