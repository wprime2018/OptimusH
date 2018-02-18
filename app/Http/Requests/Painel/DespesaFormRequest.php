<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class DespesaFormRequest extends FormRequest
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
            'descricao' => 'required|min:1|max:5',
            'filial' => 'required',
            'valor' => 'required|numeric',
            'tp_pgto' => 'required|numeric',
            'tp_desp' => 'required|numeric',
            'data_pgto' => 'required',
        ];
    }

    public function messages() {

        return [
            'descricao.required' => 'Descrição é obrigatório!',
            'filial.required'    => 'É necessário informar a filial responsável pela despesa.',
            'valor.required'     => 'Valor para a despesa é obrigatório!',
            'tp_pgto.required'   => 'Informe o tipo de pagamento da despesa.',
            'tp_desp.required'   => 'Informe o tipo de despesa.',
            'data_pgto.required' => 'É necessário informar a data de pagamento da despesa.',
        ];
    }
}
