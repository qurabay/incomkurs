<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class WebCheck
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

        if (session()->has('admin') || session()->has('moderator')){
            return $next($request);
        }else{
           return redirect()->route('admin.login')->withErrors('Нет доступа, введите логин и пароль');
        }
    }
}
