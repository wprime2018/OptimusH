<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\TpDespesas;
use App\Models\Painel\Filiais;

class Despesas extends Model
{
    public function filial()
    {
        return $this->hasOne(Filiais::class);
    }
    public function tipo_despesa()
    {
        return $this->hasOne(TpDespesas::class);
    }

    protected $table = 'tb_despesas';
    // Abaixo informo quais os campos da tabela podem ser preenchidas
    protected $fillable = [
            'descricao', 
            'filial_id', 
            'valor', 
            'tp_pgto', 
            'tp_desp_id', 
            'qtde_parcelas',
            'fixa',
            'obs',
            'data_pgto',
            'path_comp',
            'user_cad'
    ];
}
