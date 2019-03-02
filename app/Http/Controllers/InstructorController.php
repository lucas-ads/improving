<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Course;
use App\Unit;
use App\Http\Requests\RegisterCourseRequest;
use Carbon\Carbon;

class InstructorController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkinstructorauth');
    }

    public function index(){
        $user = Auth::user();
        
        return view('instructor.home')->with('user',$user);
    }

    public function showCreateCourseForm(){
        $user = Auth::user();
        $categories = Category::all();

        return view('instructor.registerCourse')->with([
            'user'=>$user,
            'categories'=>$categories
        ]);
    }

    public function registerCourse(RegisterCourseRequest $request){
        $user = Auth::user();

        $course = new Course;

        $course->title = $request->input('title');
        $course->workload = $request->input('workload');
        $course->category_id = $request->input('category');
        $course->keywords = $request->input('keywords');
        $course->icon = str_replace_first('public','storage',$request->icon->store('public'));

        $course->status = 1;
        $course->user_id = $user->id;

        $course->save();

        return redirect()->route('instructor-show-course',$id=$course->id);
    }

    public function showUpdateCourseForm($id){
        $user = Auth::user();
        $course = Course::find($id);
        $categories = Category::all();

        return view('instructor.updateCourse')->with([
            'user'=>$user,
            'course'=>$course,
            'categories'=>$categories
        ]);
    }

    public function updateCourse(RegisterCourseRequest $request, $id){
        $user = Auth::user();

        $course = Course::find($id);

        $course->title = $request->input('title');
        $course->workload = $request->input('workload');
        $course->category_id = $request->input('category');
        $course->keywords = $request->input('keywords');

        if($request->has('icon')){
            Storage::delete(str_replace_first('storage','public',$course->icon));
            $course->icon = str_replace_first('public','storage',$request->icon->store('public'));
        }        

        $course->save();

        return redirect()->route('instructor-show-course',$id=$id);
    }

    public function deleteCourse($id){
        $course = Course::find($id);
        if($course->status == 1){
            foreach($course->units as $unit){
                $unit->items()->delete();
            }
            $course->units()->delete();
            Storage::delete(str_replace_first('storage','public',$course->icon));
            $course->delete();
        }

        return redirect()->route('instructor-home');
    }

    public function publishCourse($id){
        $course = Course::find($id);
        $course->status = 2;

        
        if($course->published_at==null){
            $course->published_at = Carbon::now()->toDateTimeString();
        }

        $course->save();

        return redirect()->route('instructor-show-course',$id=$id);
    }

    public function suspendCourse($id){
        $course = Course::find($id);

        if($course->status!=1){
            $course->status = 3;
            $course->save();
        }

        return redirect()->route('instructor-show-course',$id=$id);
    }

    public function disableCourse($id){
        $course = Course::find($id);

        if($course->status!=1){
            $course->status = 4;
            $course->save();
        }

        return redirect()->route('instructor-show-course',$id=$id);
    }

    public function showCourse($id){
        
        return view('instructor.showCourse')->with([
            'user'=>Auth::user(),
            'course'=>Course::find($id)
        ]);
    }

    public function showCourseContent($id){
        return view('instructor.showCourseContent')->with([
            'user'=>Auth::user(),
            'course'=>Course::find($id)
        ]);
    }

    public function showFormEditContent($id){
        return view('instructor.formEditContent')->with([
            'user'=>Auth::user(),
            'course'=>Course::find($id)
        ]);
    }

    public function addUnit(Request $request, $id){
        $course = Course::find($id);
        if($course->status==1){
            if(isset($request->title) && strlen($request->title)<=60){
                $unit = new Unit;
                $unit->title = $request->title;
                $unit->course_id = $course->id;
                $unit->position = count($course->units)+1;
                if($unit->save()){
                    return '{"status":true, "id": '. $unit->id .'}';
                }
                return '{"status":false, "erro": "Erro desconhecido."}';
            }
            return '{"status":false, "erro": "O título deve ter no máximo 60 caracteres."}';
        }
        return '{"status":false, "erro": "Este curso não pode ser editado."}';
    }

    public function orderUnits(Request $request, $id){
        $course = Course::find($id);

        if($course->status==1){
            $idsCourseTemp = $course->unitsIds();
            $idsRequestTemp = $request->units;

            sort($idsCourseTemp);
            sort($idsRequestTemp);

            if($idsCourseTemp==$idsRequestTemp){
                foreach($request->units as $pos => $id){
                    $unit = Unit::find($id);
                    $unit->position = ($pos+1);
                    $unit->save();
                }
                return '{"status":true}';        
            }
            return '{"status":false, "erro": "A requisição não trouxe o posicionamento de todas as unidades."}';    
        }
        return '{"status":false, "erro": "Este curso não pode ser editado."}';
    }

    public function editUnit(Request $request, $id,$idUnit){
        $unit = Unit::find($idUnit);

        if($unit!=null && $request->has('title')){
            if(strlen($request->title)>60){
                return '{"status":false, "erro": "O título deve ter no máximo 60 caracteres."}';
            }
            $unit->title=$request->title;
            $unit->save();
            return '{"status":true}';
        }
        return '{"status":false, "erro": "Erro desconhecido."}';

    }

    public function removeUnit(Request $request, $id,$idUnit){
        $unit = Unit::find($idUnit);
        $course = Course::find($id);

        if($unit!=null && $unit->course->id == $id && $course->status==1){
            $unit->items()->delete();
            foreach($course->units as $u){
                if($u->position > $unit->position){
                    $u->position = $u->position - 1;
                    $u->save();
                }
            }
            $unit->delete();
            return '{"status":true,"msg": "Unidade Excluida"}';
        }

        return '{"status":false, "erro": "Erro desconhecido."}';
    }
}
