<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Course;
use App\Subscription;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;

class PdfViewController extends Controller{
    public function index($id){
        $user = Auth::user();
        $course = Course::find($id);

        if($course!=null){
            $subscription = Subscription::findSubscription($user->id,$course->id)->first();
            if($subscription!=null && $subscription->completed_at!=null){
                return $this->generateCertificate($user,$subscription);
            }
        }

        return redirect()->route('user-home');
    }

    public function validateCertificate(Request $request){
        $validator = Validator::make($request->all(),[],[]);

        if($request->has('codeCertificate') && $request->has('cpfUser')){
            $subscription = Subscription::all()->where('validationCode',$request->codeCertificate)->first();
            if($subscription!=null){
                if($subscription->user->cpf == $request->cpfUser){
                    return $this->generateCertificate($subscription->user, $subscription,"stream");
                }else{
                    $validator->errors()->add('cpfUser', 'O CPF informado não corresponde ao CPF do detentor do certificado.');
                }
            }else{
                $validator->errors()->add('codeCertificate', 'Não foi encontrado nenhum certificado com o código informado.');
            }
        }

        return redirect()->route('guest-form-validate-certificate')
                        ->withErrors($validator)
                        ->withInput();
    }

    public function downloadCertificate(Request $request, $id, $idSubscription){
        $subscription = Subscription::find($idSubscription);

        if($subscription!=null && $subscription->user->id == $id && $subscription->completed_at!=null){
            return $this->generateCertificate(User::find($id),$subscription,'stream');
        }

        return redirect()->route('admin-home');
    }

    private function generateCertificate($user, $subscription,$mode="download"){
        if($subscription->validationCode==null){
            $subscription->setValidationCode();
            $subscription->save();
        }

        $pdf = PDF::loadView('layouts.certificate',[
            'user'=>$user,
            'subscription'=>$subscription
        ]);
        $pdf->setPaper('A4', 'landscape');
        if($mode=="stream"){
            return $pdf->stream('certificate'.$subscription->course->id.'.pdf');
        }else{
            return $pdf->download('certificate'.$subscription->course->id.'.pdf');
        }
        
    }
}
