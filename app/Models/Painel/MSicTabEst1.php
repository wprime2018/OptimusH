<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\Estoque;
use App\Models\Painel\Setores;

class MSicTabEst1 extends Model
{
    protected $fillable = [
        'Controle',
        'Codigo',
        'CodInterno',
        'Produto',
        'LkSetor',
        'Fabricante',
        'LkFornec',
        'PrecoCusto',
        'CustoMedio',
        'PrecoVenda',
        'Quantidade',
        'EstMinimo',
        'Unidade',
        'Lucro',
        'Comissao',
        'Moeda',
        'UltReaj',
        'Foto',
        'Obs',
        'NaoSaiTabela',
        'Inativo',
        'CodIPI',
        'IPI',
        'CST',
        'ICMS',
        'BaseCalculo',
        'PesoBruto',
        'PesoLiq',
        'LkModulo',
        'Armazenamento',
        'QntEmbalagem',
        'ELV',
        'Previsao',
        'DataFoto',
        'DataInc',
        'LkUserInc',
        'CodEx',
        'IVA_ST',
        'PFC',
        'IPI_CST',
        'IPI_BaseCalc',
        'IPPT',
        'IAT',
        'DataUltMov',
        'EAD',
        'cEAN',
        'cEANTrib',
        'cProdANP',
        'CEST',
        'Origem',
        'created_at'
      ];
        public function prodEstoque() {
            return $this->hasMany(Estoque::Class, 'LkProduto', 'Controle')->orderBy('filial_id');
        }
        public function setor()  {
            return $this->hasOne(Setores::class, 'LkSetor', 'Controle');
        }
}
