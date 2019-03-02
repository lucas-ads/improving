<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Zend\Diactoros\Request;

class RegisterCourseRequest extends FormRequest
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
        
        return [
            'title' => ['required','max:50','min:10'],
            'workload' => ['required','integer','min:10','max:400'],
            'category' => ['required','integer','exists:categories,id'],
            'keywords' => ['required','regex:/[a-záàâãéèêíïóôõöúçñ\-]+$/i'],
            'icon' => ['mimetypes:image/png','dimensions:min_width=200,min_height=200,ratio=1/1']
        ];
    }

    public function messages(){
        return [
            'workload.min'=>'A carga horária não pode ser inferior a 10 horas.',
            'workload.max'=>'A carga horária não pode ser superior a 400 horas.',
            'category.required'=>'Informe a categoria do curso.',
            'category.integer'=>'Informe a categoria do curso.',
            'category.exists'=>'A categoria informada não foi encontrada.',
            'icon.mimetypes' => 'O ícone deve estar no formato PNG.',
            'icon.dimensions' => 'A imagem deve ter tamanho mínimo de 200x200 e formato 1/1.'
        ];
    }
}
