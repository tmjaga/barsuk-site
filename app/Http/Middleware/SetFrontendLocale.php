<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFrontendLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('frontend_locale');
        $languages = array_keys(config('logat.languages'));

        if (! $locale) {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            $locale = in_array($browserLocale, $languages) ? $browserLocale : 'en';

            session(['frontend_locale' => $locale]);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
