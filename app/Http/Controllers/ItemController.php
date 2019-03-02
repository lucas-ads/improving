<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Course;
use App\Unit;
use App\Item;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkinstructorauth');
    }

    public function addVideoLessonOrLink(Request $request, $id, $idUnit){

        $item = new Item;

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'url' => 'required|url',
            'type' => 'required|min:1|max:2'
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => false,
                'erro' => $validator->messages()->first()
            ],200);
        }

        $unit=Unit::find($idUnit);

        $item->type=$request->type;
        $item->title = $request->title;
        $item->resource = $request->url;
        $item->unit_id = $idUnit;
        $item->position = count($unit->items) + 1;

        if($item->save()){   
            return response()->json([
                "status" => true,
                "id" => $item->id,
            ],200);
        }else{
            return response()->json([
                "status" => false,
                'erro' => "Erro desconhecido"
            ],200);
        }
    }

    public function editVideoLessonOrLink(Request $request,$id, $idItem){
        $course = Course::find($id);
        $item = Item::find($idItem);
        
        if($course!=null && $item != null && ($item->unit->course->id == $id)){
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:100',
                'url' => 'required|url',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    "status" => false,
                    'erro' => $validator->messages()->first()
                ],200);
            }
    
            $item->title = $request->title;
            $item->resource = $request->url;
    
            if($item->save()){   
                return response()->json([
                    "status" => true
                ],200);
            }
        }

        return response()->json([
            "status" => false,
            'erro' => "Erro desconhecido, recarregue a página e tente novamente."
        ],200);
    }

    public function removeItem(Request $request,$id, $idItem){
        $item = Item::find($idItem);
        $course = Course::find($id);

        if($item!=null && $item->unit->course->id == $id && $course->status==1){
            $items = $item->unit->items;
            foreach($items as $i){
                if($i->position > $item->position){
                    $i->position = $i->position - 1;
                    $i->save();
                }
            }
            $item->delete();
            return '{"status":true,"msg": "Item Excluido"}';
        }

        return '{"status":false, "erro": "Erro desconhecido."}';
    }

    public function showFormCreateTest(Request $request, $id, $idUnit){
        $course = Course::find($id);

        $unit = Unit::find($idUnit);

        if($unit!=null && $course->status==1 && $unit->course->id == $id){
            return view('instructor.formCreateEditTest')->with([
                'user'=>Auth::user(),
                'course'=>Course::find($id),
                'unit' => $unit
            ]);
        }

        return redirect()->route('instructor-show-form-content',['id'=> $id]);
    }

    public function addTest(Request $request, $id, $idUnit){
        $item = new Item;
        $unit = Unit::find($idUnit);

        $item->title = $request->title;
        $item->type = 3;
        $item->resource = $request->test;

        $item->unit_id = $idUnit;
        $item->position = count($unit->items) + 1;

        $item->save();

        return redirect()->route('instructor-show-form-content',$id=$id);
    }

    public function showFormEditTest(Request $request, $id, $idItem){
        $course = Course::find($id);
        $item = Item::find($idItem);

        if($item!=null && $item->unit->course->id == $id){
            return view('instructor.formCreateEditTest')->with([
                'user'=>Auth::user(),
                'course'=>$course,
                'unit' => $item->unit,
                'item' => $item
            ]);      
        }else{
            return redirect()->back();
        }
    }

    public function editTest(Request $request, $id, $idItem){
        $course = Course::find($id);
        $item = Item::find($idItem);

        if($item!=null && $item->unit->course->id == $id){
            $item->title = $request->title;
            $item->resource = $request->test;
    
            $item->save();
        }

        return redirect()->route('instructor-show-form-content',$id=$id);
    }

    public function orderItems(Request $request, $id){
        $course= Course::find($id);
        $arrayAux=[];

        if($course->status==1 && $request->has('unitsItems')){
            $itemsIds = $course->itemsIds();
            sort($itemsIds);

            foreach($request->unitsItems as $unit){
                foreach($unit as $item){
                    $arrayAux[]=$item;
                }
            }

            sort($arrayAux);

            if($arrayAux==$itemsIds){
                $this->updateOrderItems($course,$request->unitsItems);
                return response()->json([
                    "status" => true,
                ],200);
            }

        }

        return response()->json([
            "status" => false,
            'erro' => "Erro de consistência, recarregue a página."
        ],200);
    }

    private function updateOrderItems($course,$unitsItems){
        
        $cont=0;
        foreach($course->units as $unit){
            if(isset($unitsItems[$cont]) && $unit->itemsIds()!=$unitsItems[$cont]){
                $contpos=1;
                foreach($unitsItems[$cont] as $id){
                    $item = Item::find($id);
                    $item->position = $contpos;
                    $item->unit_id = $unit->id;
                    $item->save();
                    $contpos++;
                }
            }
            $cont++;
        }
    }
}
