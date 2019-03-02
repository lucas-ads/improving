<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Subscription;
use App\Course;
use App\Item;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $courses = Course::all()->whereIn('status',[2,3]);
        
        return view('user.home')->with([
            'user'=>$user,
            'courses'=>$courses
        ]);
    }

    public function showCourse(Request $request, $id){
        return view('user.showCourse')->with([
            'user'=>Auth::user(),
            'course'=>Course::find($id)
        ]);
    }
    
    public function registerInCourse(Request $request, $id){
        $user = Auth::user();
        $course = Course::find($id);

        if(count(Subscription::findSubscription($user->id,$course->id))==0){
            $subscription = new Subscription;
            $subscription->user_id = $user->id;
            $subscription->course_id = $course->id;
            
            $subscription->save();
        }

        return redirect()->route('user-show-course',$id=$id);
    }

    public function myCourses(){
        $user=Auth::user();

        return view('user.myCourses')->with([
            'user'=>Auth::user(),
            'subscriptions'=>$user->subscriptions
        ]);
    }

    public function continueCourse(Request $request, $id, $idItem=null){
        $user = Auth::user();
        $course = Course::find($id);

        $subscription=Subscription::findSubscription($user->id, $id);
        if(count($subscription)>0){
            $subscription = $subscription->first();

            if($idItem!=null){
                $item = Item::find($idItem);
                if($item!=null && $item->unit->course->id == $id){

                    $access = $subscription->items->where('id',$idItem)->first();
                    if($access==null && $item->type != 3){
                        $subscription->items()->attach($idItem);

                        $subscription = Subscription::findSubscription($user->id, $id)->first();
                        if($subscription->getProgress()==100){
                            $subscription->conclude();
                            $subscription->save();
                        }
                    }                    

                    return view('user.executeCourse',[
                        'user' => $user,
                        'course'=> $course,
                        'thisItem'=> $item,
                        'subscription'=>$subscription
                    ]);
                }
            }else{
                $items = $subscription->course->items();
                foreach($items as $item){
                    if(count($item->subscriptions->where('id',$subscription->id))==0){
                        return redirect()->route('user-continue-course',[
                            $id=$id,
                            $idItem=$item->id
                        ]);
                    }
                }
                if(count($items)>0){
                    return redirect()->route('user-continue-course',[
                        $id=$id,
                        $idItem=$items->first()->id
                    ]);
                }
            }
        }
        return redirect()->route('user-home');
    }

    public function responseQuestion(Request $request, $id, $idQuestion){
        $user = Auth::user();
        $question = Item::find($idQuestion);
        $subscription=Subscription::findSubscription($user->id, $id)->first();

        if($question!=null && $question->unit->course_id == $id && $subscription!=null){
            $access = $subscription->items->where('id',$idQuestion)->first();
            if($access==null){
                $subscription->items()->attach($idQuestion,['points'=>$request->nota]);

                $subscription = Subscription::findSubscription($user->id, $id)->first();

                if($subscription->getProgress()==100){
                    $subscription->conclude();
                    $subscription->save();

                    return response()->json([
                        "nota" => $request->nota,
                        "concluido"=>"true"
                    ],200);
                }
            }else{
                $subscription->items()->updateExistingPivot($idQuestion, ['points'=>$request->nota]);
            }
        }

        return response()->json([
            "nota" => $request->nota,
            "concluido"=>"false"
        ],200);
    }

    public function showFormFeedback(Request $request, $id){
        $user = Auth::user();
        $subscription = Subscription::findSubscription($user->id,$id)->first();
        if($subscription==null || $subscription->completed_at==null){
            return redirect()->route('user-home');
        }

        return view('user.feedbackCourse',[
            'user'=>$user,
            'subscription'=>$subscription,
            'course'=>$subscription->course
        ]);
    }

    public function saveFeedback(Request $request,$id){
        $user = Auth::user();
        $subscription = Subscription::findSubscription($user->id,$id)->first();

        if($subscription==null || $subscription->completed_at==null || !$request->has('inputStar') || $request->inputStar<1 || $request->inputStar>5){
            return redirect()->route('user-home');
        }

        $subscription->starsCourse = $request->inputStar;

        if($request->has('comment') && strlen($request->comment)>0){
            $subscription->comment = $request->comment;
        }

        $subscription->save();
        return redirect()->route('user-show-course',$id=$id);
    }

    public function profile(){
        return view('user.profile',[
            'user'=>Auth::user()
        ]);
    }

    public function setImageProfile(Request $request){
        $messages = [
            'newImage.dimensions'=> 'A imagem tem dimensões inválidas.'
        ];
        $validator = Validator::make($request->all(), [
            'newImage' => ['mimetypes:image/png,image/jpeg','dimensions:min_width=200,min_height=200,ratio=1/1']
        ],$messages);

        if($validator->fails()){
            return redirect()->route('user-profile')->withErrors($validator);
        }

        $user = Auth::user();

        if($user->image_profile != null){
            Storage::delete(str_replace_first('storage','public',$user->image_profile));
        }

        $user->image_profile = str_replace_first('public','storage',$request->newImage->store('public'));

        $user->save();

        return redirect()->route('user-profile');
    }
}