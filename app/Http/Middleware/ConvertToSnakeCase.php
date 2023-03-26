<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConvertToSnakeCase
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // リクエストパラメータを取得
        $data = $request->all();
        // リクエストパラメータのキーをキャメルケースからスネークケースに変換する関数
        $data = array_combine(array_map(function ($key) {
            return Str::snake($key);
        }, array_keys($data)), $data);
        // 変換したリクエストパラメータを新しいリクエストオブジェクトにセット
        $request->replace($data);
        // 次のミドルウェアに新しいリクエストオブジェクトを渡す
        return $next($request);
    }
}
