<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Course extends Model
{
    protected $dates = ['published_at', 'created_at','uploaded_at'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function units(){
        return $this->hasMany('App\Unit')->OrderBy('position');
    }

    public function unitsIds(){
        $listIds=[];

        foreach ($this->units as $unit) {
            $listIds[]=$unit->id;
        }
        return $listIds;
    }

    public function items(){
        $itemsCollection = new Collection();
        
        foreach ($this->units as $unit) {
            $itemsCollection = $itemsCollection->merge($unit->items);
        }

        return $itemsCollection;
    }

    public function itemsIds(){
        $listIds=[];

        foreach ($this->units as $unit) {
            foreach ($unit->items as $item) {
                $listIds[]=$item->id;
            }
        }

        return $listIds;
    }

    public function getPublicationDate(){
        if($this->published_at){
            return $this->published_at->format('d/m/Y');
        }else{
            return '--';
        }
    }

    public function getUrlIcon(){
        return url('/')."/".$this->icon;
    }

    public function getStatusLegend(){
        switch ($this->status){
            case 1:
                return 'Em criação';
            case 2:
                return "Ofertado";
            case 3:
                return "Suspenso";
            case 4:
                return "Inativo";
        }
    }

    public function getStatusDescription(){
        switch ($this->status){
            case 1:
                return 'O curso está em fase de criação: Ainda não foi disponibilizado na plataforma, pode ser livremente editado, excluído ou publicado por seu autor.';
            case 2:
                return 'O curso está publicado na plataforma: Os interessados podem matricular-se; As edições são limitadas; O curso pode ser suspenso ou desativado pelo autor.';
            case 3:
                return 'A oferta do curso foi suspensa: Os matriculados continuam possuindo acesso, mas novas matrículas não são possíveis; As edições são limitadas; O autor pode republica-lo ou desativa-lo.';
            case 4:
                return 'O curso está desativado: Não é mais possível realizar matrículas ou acessá-lo; As edições são limitadas; O autor pode republica-lo ou permitir acesso aos matriculados alterando-o para "Suspenso".';
        }
    }

    public function getStatusColor(){
        switch ($this->status){
            case 1:
                return 'text-primary';
            case 2:
                return "text-success";
            case 3:
                return "text-danger";
            case 4:
                return "text-secondary";
        }
    }

    public function getKeywordsFormated(){
        return str_replace(' ','", "','"'.$this->keywords.'"');
    }

}
