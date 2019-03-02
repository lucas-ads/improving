<?php

namespace App\Http\Middleware;

use Closure;
use App\Course;
use App\Subscription;
use App\User;
use Illuminate\Support\Facades\Auth;

class CheckCanAccessCourse
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

        $course = Course::find($request->route()->id);

        if($course!=null){
            if($course->status==2){
                return $next($request);
            }
            $subscription = Subscription::findSubscription($user->id,$course->id);

            if($course->status==3 && count($subscription)!=0){
                return $next($request);
            }
        }

        return redirect()->route('user-home');
        
    }
}
