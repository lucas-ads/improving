<?php

namespace App\Http\Middleware;

use Closure;
use App\Course;
use Illuminate\Support\Facades\Auth;

class CheckCanHandleCourse
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

        if($course!=null && ($course->user_id == $user->id)){
            return $next($request);
        }

        return redirect()->route('instructor-home');
    }
}
