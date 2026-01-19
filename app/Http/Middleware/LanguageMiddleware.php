<?php

namespace App\Http\Middleware;

use Closure;

use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\Setting\Language;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{

    public function handle($request, Closure $next)
    {
        session()->put('lang', $this->getCode());
        app()->setLocale(session('lang', $this->getCode()));
        return $next($request);
    }

    public function getCode()
    {
        if (session()->has('lang')) {
            return session('lang');
        }
        $language = Language::where('is_default', Status::ENABLE)->first();
        return $language ? $language->code : 'en';
    }
}
