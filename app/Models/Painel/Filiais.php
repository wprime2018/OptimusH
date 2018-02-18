<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\Despesas;

class Filiais extends Model
{
    /*public function despesas()
    {
        return $this->hasMany(Despesas::class);
    }*/
    
    protected $table = 'tb_filiais';
    // Abaixo informo quais os campos da tabela podem ser preenchidas
    protected $fillable = [
            'codigo',
            'fantasia',
            'razao_social',
            'cep',
            'logradouro',
            'numero',
            'compl',
            'bairro',
            'cidade',
            'estado',
            'ibge',
            'cnpj',
            'ie', 
            'im',
            'ativo' 
		    ];
}
