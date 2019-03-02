<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class CheckCanHandleUser
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
        $user = Auth::user();

        $userHandled = User::find($request->route()->id);

        if($userHandled!=null && ($user->isRoot() || ($user->isAdmin() && !$userHandled->isAdmin()))){
            return $next($request);
        }

        return redirect()->route('admin-home');
    }
}
