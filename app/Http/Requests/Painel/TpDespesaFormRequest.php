<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class TpDespesaFormRequest extends FormRequest
{
   /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
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
            'descricao' => 'required|min:3|max:50',
        ];
    }

    public function messages() {

        return [
            'descricao.required' =>'Descrição é obrigatório!',
        ];
    }
}
