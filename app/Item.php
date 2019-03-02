<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function getType(){
        if($this->type==1){
            return 'videolesson';
        }
        if($this->type==2){
            return 'link';
        }
        if($this->type==3){
            return 'test';
        }
    }

    public function prevItem(){
        if($this->position > 1){
            $prevItem = Item::all()->where('unit_id',$this->unit_id)->where('position',$this->position - 1)->first();
            return $prevItem;
        }else{
            if($this->unit->position>1){
                $unitPrev = Unit::all()->where('course_id',$this->unit->course_id)->where('position',$this->unit->position - 1)->first();

                $prevItem = Item::all()->where('unit_id',$unitPrev->id)->where('position',count($unitPrev->items))->first();
                return $prevItem;
            }
        }
    }

    public function nextItem(){
        if($this->position < count($this->unit->items)){
            $nextItem = Item::all()->where('unit_id',$this->unit_id)->where('position',$this->position + 1)->first();
            return $nextItem;
        }else{
            if($this->unit->position < count($this->unit->course->units)){
                $unitNext = Unit::all()->where('course_id',$this->unit->course_id)->where('position',$this->unit->position + 1)->first();

                $nextItem = Item::all()->where('unit_id',$unitNext->id)->where('position',1)->first();
                return $nextItem;
            }
        }
    }

    public function unit(){
        return $this->belongsTo('App\Unit');
    }

    public function subscriptions(){
        return $this->belongsToMany('App\Subscription');
    }
}
