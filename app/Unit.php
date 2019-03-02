<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function course(){
        return $this->belongsTo('App\Course');
    }

    public function items(){
        return $this->hasMany('App\Item')->OrderBy('position');
    }

    public function itemsIds(){
        $listIds=[];

        foreach ($this->items as $item) {
            $listIds[]=$item->id;
        }

        return $listIds;
    }
}
