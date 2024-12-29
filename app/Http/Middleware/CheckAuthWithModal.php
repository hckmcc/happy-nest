<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuthWithModal
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            session()->flash('showAuthModal', true);
            return response()->json([
                'needAuth' => true,
                'currentUrl' => $request->fullUrl() // опционально отправляем текущий URL
            ], 401);
        }

        return $next($request);
    }
}
