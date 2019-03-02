<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $dates = ['completed_at'];

    public function course(){
        return $this->belongsTo('App\Course');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function items(){
        return $this->belongsToMany('App\Item')->withPivot('points');
    }

    public static function findSubscription($idUser, $idCourse){
        return self::all()->where('user_id',$idUser)->where('course_id',$idCourse);
    }

    public function getProgress(){
        $quantItems = count($this->course->items());
        $quantItems = $quantItems>0?$quantItems:1;
        return (count($this->items)/$quantItems)*100;
    }

    public function getPoints(){
        $somaNota=0;
        $cont=0;
        foreach($this->items->where('type',3) as $item){
            $somaNota+=$item->pivot->points;
            $cont++;
        }
        if($cont==0){
            $media="--";
        }else{
            $media=($somaNota/$cont);
        }

        return $media;
    }

    public function conclude(){
        $this->completed_at = Carbon::now()->toDateTimeString();
    }

    public function setValidationCode(){
        if($this->validationCode==null && $this->completed_at!=null){
            $this->validationCode = ($this->id % 10).uniqid();
        }
    }
}
