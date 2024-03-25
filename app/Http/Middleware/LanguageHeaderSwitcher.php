<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageHeaderSwitcher
{

    public function handle(Request $request, Closure $next)
    {
        $langs = LanguageService::getAllLanguage();
        $lang_from_header =$request->header('language');

        if(in_array($lang_from_header , $langs)){
        App::setLocale($request->header('language'));
            $request['language_id'] = $lang_from_header;
        } else {
            App::setLocale(config('app.fallback_locale'));
            $request['language_id'] = $langs[0];
        }

        return $next($request);
    }
}
