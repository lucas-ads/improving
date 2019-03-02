<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use Validator;
use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkadminauth');
    }

    public function index(){
        $user = Auth::user();
        $roles = ['100','110'];

        if($user->isRoot()){
            $roles[] = '111';
            $roles[] = '101';
        }

        return view('admin.home',[
            'user'=>$user,
            'users'=>User::all()->whereIn('role',$roles)
        ]);
    }

    public function showUser(Request $request, $id){
        $user = Auth::user();
        $otherUser = User::find($id);

        return view('admin.showUser',[
            'user'=>$user,
            'otherUser' => $otherUser
        ]);
    }

    public function showRegistrationForm(){
        $user = Auth::user();
        return view('admin.registerUser')->with('user',$user);
    }

    public function showUpdateUserForm($id){
        $user = Auth::user();

        $userUpdate = User::find($id);

        return view('admin.updateUser')->with([
            'user'=> $user,
            'userUpdate'=> $userUpdate
        ]);
    }

    public function registration(RegisterUserRequest $request){
        $user = Auth::user();

        $newUser = new User;

        $newUser->name = $request->input('name');
        $newUser->email = $request->input('email');
        $newUser->cpf = $request->input('cpf');
        $newUser->date_of_birth = $request->input('dateOfBirth');
        $newUser->role = "1" 
            .($request->has('instructorCheck') ? "1" : "0")
            .($request->has('adminCheck') && $user->isRoot() ? "1" : "0");
                
        $newUser->status = 1;

        $newUser->save();

        return redirect()->route('admin-form-register-user');
    }

    public function updateUser(RegisterUserRequest $request,$id){
        $user = Auth::user();

        $userUpdate = User::find($id);

        $userUpdate->name = $request->input('name');

        if($userUpdate->email != $request->input('email')){
            $userUpdate->email = $request->input('email');
            $userUpdate->email_verified_at = null;
        }

        if($request->status=="active"){
            $userUpdate->status=1;
        }else{
            if($request->status=="inactive"){
                $userUpdate->status=0;
            }
        }
        
        $userUpdate->cpf = $request->input('cpf');
        $userUpdate->date_of_birth = $request->input('dateOfBirth');

        $adminRole = $userUpdate->role;

        $userUpdate->role = "1" 
            .($request->has('instructorCheck') ? "1" : "0")
            .($request->has('adminCheck') && $user->isRoot() ? "1" : "0");

        $userUpdate->save();

        return redirect()->route('admin-update-user',['id'=>$id]);
    }
}
