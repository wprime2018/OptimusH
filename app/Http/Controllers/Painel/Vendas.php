<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class Vendas extends Controller
{
    public function indexVendas()
    {
        $Vendas = MSicTabEst3A::join('m_sic_tab_est7s', 'm_sic_tab_est3_as.LkReceb', '=', 'm_sic_tab_est7s.Controle')
                                ->join('tb_filiais', 'm_sic_tab_est3_as.filial_id', '=', 'tb_filiais.id')
                                ->join('m_sic_tab_vends', 'm_sic_tab_est3_as.LkVendedor', '=', 'm_sic_tab_vends.Controle')
                                ->select('m_sic_tab_est3_as.*', 'tb_filiais.fantasia','m_sic_tab_vends.Nome','m_sic_tab_est7s.Recebimento')
                                ->with('prodVendidos')
                                ->limit(100)
                                ->get();
        $ListFiliais = Filiais::get();
        return view('painel.vendas.Vendas', compact('ListFiliais','Vendas'));
    }
}
