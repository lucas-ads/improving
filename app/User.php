<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MessagePasswordReset;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'date_of_birth'
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MessagePasswordReset($token));
    }

    public function courses(){
        return $this->hasMany('App\Course')->OrderBy('status');
    }

    public function subscriptions(){
        return $this->hasMany('App\Subscription')->orderBy('created_at','desc');
    }

    public function getFirstName(){
        return explode(' ',$this->name)[0];
    }

    public function getUrlImageOrBlue(){
        if($this->image_profile != null){
            return url('/')."/".$this->image_profile;
        }else{
            return url('/')."/images/icon-profile-azul.png";
        }
    }

    public function getUrlImageOrWhite(){
        if($this->image_profile != null){
            return url('/')."/".$this->image_profile;
        }else{
            return url('/')."/images/icon-profile-branco.png";
        }
    }

    public function getRolesArray(){
        $role[]='Usuário';
        if($this->isInstructor()){
            $role[]='Instrutor';
        }
        if($this->isAdmin()){
            $role[]='Administrador';
        }
        if($this->isRoot()){
            $role[]='Root';
        }
        return $role;
    }

    private function replaceByPos($str, $pos, $c){
        return substr($str, 0, $pos) . $c . substr($str, $pos);
    }

    public function getFormatedCpf(){
        $cpff = $this->replaceByPos($this->cpf, 9,'-');
        $cpff = $this->replaceByPos($cpff, 6,'.');
        $cpff = $this->replaceByPos($cpff, 3,'.');
        return $cpff;
    }

    public function isAdmin(){
        if($this->role[2]!="0"){
            return true;
        }
        return false;
    }

    public function isRoot(){
        if($this->role[2]=="2"){
            return true;
        }
        return false;
    }

    public function isInstructor(){
        if($this->role["1"]=="1"){
            return true;
        }
        return false;
    }

    public function isOnlyUser(){
        if($this->role=="100"){
            return true;
        }
        return false;
    }

    public function isOnlyInstructor(){
        if($this->role=="110"){
            return true;
        }
        return false;
    }

    public function isOnlyAdmin(){
        if($this->role=="101" || $this->role=="102"){
            return true;
        }
        return false;
    }
}
