<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageHeaderSwitcher
{
 
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('language') == 'ar' || $request->header('language') == 'en') {
            App::setLocale($request->header('language'));
            $request['language_id'] = $request->header('language') == 'ar' ? 2 : 1;
        } else {
            App::setLocale(config('app.fallback_locale'));
            $request['language_id'] = 1;
        }

        return $next($request);
    }
}
