<?php

namespace App\Http\Middleware;

use Closure;
use App\Course;
use App\Unit;
use Illuminate\Support\Facades\Auth;

class CheckCanAddItem
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

        $unit = Unit::find($request->route()->idUnit);

        $course = Course::find($request->route()->id);

        if($unit==null){
            return response()->json(['status' => false,'erro'=>'A unidade não existe.'],200);
        }
        
        if($course->user_id != $user->id){
            return response()->json(['status' => false,'erro'=>'Você não tem permissão para editar este curso.'],200);
        }

        if($unit->course_id != $course->id){
            return response()->json(['status' => false,'erro'=>'A unidade não pertence ao curso informado.'],200);
        }

        if($course->status!=1){
            return response()->json(['status' => false,'erro'=>'Você não pode adicionar conteúdo a um curso já publicado.'],200);
        }

        return $next($request);
    }
}
