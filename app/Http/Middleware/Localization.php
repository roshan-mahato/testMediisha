<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) 
        {
            \App::setLocale(session()->get('locale'));
        }
        else{
            $language = \App\Models\Setting::first()->language;
            $direction = \App\Models\Language::where('name',$language)->first()->direction;
            App::setLocale($language);
            session()->put('locale',$language);
            session()->put('direction',$direction);
        }
        return $next($request);
    }
}
