<?php

namespace App\Http\Middleware;

use Closure;
use App\models\langs_m;
use Illuminate\Http\RedirectResponse;

class ChangeLanguageLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = langs_m::where('lang_title', strtolower($request->locale))->first();

        session()->put('current_lang_id', $language->lang_id);
        session()->put('language_locale', $language->lang_title);

        return $next($request);
    }
}
