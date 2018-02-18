<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;

class MSicTabEst3B extends Model
{
    protected $fillable = [
        'Controle',
        'filial_id',
        'LkEst3A',
        'Quantidade',
        'LkProduto',
        'Total',
        'TotVenda',
        'Lucro',
        'Acrescimo',
        'DataInc',
        'ICMS',
        'QuantCanc',
        'ValorCanc',
        'CFOPProd',
        'LkPrecoProd',
        'ComissaoProd',
        'Previsao',
        'created_at'
    ];
    public function vendas()
    {
        return $this->belongsTo(MSicTabEst3A::Class,'LkEst3A', 'Controle');
       
    }
}
