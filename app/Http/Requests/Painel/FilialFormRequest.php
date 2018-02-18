<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;

class FilialFormRequest extends FormRequest
{
    public function authorize()
    {
        //return false;
        return true;
    }

    public function rules()
    {
        return [
            'codigo' => 'required|min:1|max:5',
            'fantasia' => 'required|min:2|max:30',
            'razao_social' => 'required|min:2|max:60',
            'numero' => 'required|numeric',
            'cep' => 'required',
            'ibge' => 'required|numeric',
            'cnpj' => 'required|cnpj',
            'ie' => 'required|numeric'
        ];
    }

    public function messages() {

        return [
            'codigo.required' =>'Código é obrigatório!',
            'fantasia.required' => 'Nome fantasia é obrigatório!',
            'razao_social.required' => 'Razão Social é obrigatório!',
            'cep.required' => 'O CEP da filial deve ser informado!',
            'numero.required' => 'O Número do endereço da filial deve ser informado!',
            'ibge.required' => 'O IBGE da filial deve ser informado!',
            'ibge.numeric' => 'O campo IBGE deve conter apenas números!',
            'cnpj.required' => 'O CNPJ da filial deve ser informado!',
            'cnpj.cnpj' => 'C.N.P.J Inválido',
            'ie.required' => 'A Inscrição Estadual da filial deve ser informada!',
            'ie.numeric' => 'A inscrição Estadual deve conter apenas números!'
        ];
    }
}
