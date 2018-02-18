<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\MSicTabEst3B;

class MSicTabEst3A extends Model
{
    protected $fillable = [
        'Controle',
        'filial_id',
        'Data',
        'LkTipo',
        'Nota',
        'Serie',
        'Pedido',
        'LkReceb',
        'LkVendedor',
        'LkCliente',
        'LkFornec',
        'TagCliente',
        'Comissao',
        'ComissaoVend',
        'Obs',
        'Venda',
        'LkUser',
        'CFOP',
        'DataNota',
        'Cancelada',
        'TipoDoc',
        'Frete',
        'ValorFrete',
        'LkTrans',
        'CGI',
        'RetTrib',
        'LkLoja',
        'LkCliM',
        'nfe',
        'NumCF',
        'NFE_CHAVE_TEST',
        'NFE_CHAVE_PROD',
        'NFE_CHAVE',
        'NFE_AMBIENTE',
        'ID',
        'StatusPagamento',
        'Revenda',
        'RevendaComissao'
    ];
    public function prodVendidos() {
        return $this->hasMany(MSicTabEst3B::Class, 'LkEst3A', 'Controle');
    }
}
