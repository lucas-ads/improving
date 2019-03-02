<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $now = date("m/d/Y");

        $compEmail = "";
        $compCpf = "";

        if($this->route()->methods["0"]=="PUT"){
            $id=$this->route()->parameters["id"];

            $compEmail=",email,".$id;
            $compCpf=",cpf,".$id;
        }

        return [
            'name' => ['required','max:100','min:5'],
            'email' => ['required','email','unique:users'.$compEmail],
            'cpf' => ['required','digits:11','unique:users'.$compCpf],
            'dateOfBirth' => ['required','date',"before:$now"]
        ];
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            $cpf = $this->cpf;
            if ($cpf == '00000000000' || 
                $cpf == '11111111111' || 
                $cpf == '22222222222' || 
                $cpf == '33333333333' || 
                $cpf == '44444444444' || 
                $cpf == '55555555555' || 
                $cpf == '66666666666' || 
                $cpf == '77777777777' || 
                $cpf == '88888888888' || 
                $cpf == '99999999999') {
                $validator->errors()->add('cpf','O CPF informado não é válido.');
            } else {
                
                for ($t = 9; $t < 11; $t++) {
                    
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf{$c} * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf{$c} != $d) {
                        $validator->errors()->add('cpf','O CPF informado não é válido.');
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'dateOfBirth.before'=>'A data informada é inválida.'
        ];
    }
}
