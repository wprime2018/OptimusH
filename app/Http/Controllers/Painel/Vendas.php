<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;
use Carbon\Carbon;
use App\Models\Painel\Filiais;
use App\Models\Painel\MSicTabEst1;      //Produtos
use App\Models\Painel\MSicTabEst7;      //Tipos de Recebimentos
use App\Models\Painel\MSicTabVend;      //Vendedores
use App\Models\Painel\MSicTabEst3A;     //Vendas
use App\Models\Painel\MSicTabEst3B;     //Produtos Vendidos

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
    public function index_vendas_pgto()
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->get();
        $ListFiliais        = $Filiais;
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        //$data1 = $request->initial_date . ' 00:00:00';
        //$data2 = $request->final_date   . ' 23:59:59';
        $data1 = '2018-03-01 00:00:00';
        $data2 = '2018-03-31 23:59:59';
        $formas = array();
 
        foreach ($Filiais as $f) {
            /*$test =& $array[1]['test'];

            $test[] = 'stack';

            $test[] = 'overflow';*/

            $tot_filial_qtde = 0;
            $tot_filial_valor = 0;
            $tot_filial_cred = 0;
            $tot_filial_qtde_cred = 0;
            $tot_filial_deb = 0;
            $tot_filial_qtde_deb = 0;
            foreach($TipoRecebimentos  as $Tr ) {
                $tot_pgto = 0;
                //$formas[$f->codigo][] = $Tr->Recebimento;
                $Vendas = MSicTabEst3A::where('LkReceb',$Tr->Controle)
                                        ->orderBy('LkReceb')
                                        ->where('filial_id',$f->id)
                                        ->where('Cancelada','0')
                                        ->wherebetween('Data',[$data1,$data2])
                                        ->with('prodVendidos')
                                        ->get();
                $tot_qtde_receb = $Vendas->count();
                if(count($Vendas)>0){
                    foreach($Vendas as $V){
                        $tot_pgto = $tot_pgto +     $V->prodVendidos->sum('Total');
                    }
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => $tot_pgto) ;
                    $tot_filial_qtde = $tot_filial_qtde + $tot_qtde_receb;
                    $tot_filial_valor = $tot_filial_valor + $tot_pgto; 
                }else{
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => 0) ;
                }
                if ($Tr->tipo == 'C') { 
                        $tot_filial_cred = $tot_filial_cred + $tot_pgto;
                        $tot_filial_qtde_cred = $tot_filial_qtde_cred + 1;
                }
                if ($Tr->tipo == 'D') {
                        $tot_filial_deb = $tot_filial_deb + $tot_pgto; 
                        $tot_filial_qtde_deb = $tot_filial_qtde_deb + 1;
                }
                if ($Tr->tipo == null) {
                        $tot_filial_cred = $tot_filial_cred + 0; 
                        $tot_filial_deb = $tot_filial_deb + 0;
                        $tot_filial_qtde_cred = $tot_filial_qtde_cred + 0;
                        $tot_filial_qtde_deb = $tot_filial_qtde_deb + 0;
                }
            }
            if ($tot_filial_qtde > 0){
                $ticket_medio = $tot_filial_valor / $tot_filial_qtde;
            } else {
                $ticket_medio = 0;
            }
            $formas[$Tr->Recebimento][$f->codigo]['Qtde_Vendas'] = $tot_filial_qtde;
            $formas[$Tr->Recebimento][$f->codigo]['TicketM'] = $ticket_medio;
            $formas[$Tr->Recebimento][$f->codigo]['Cred'] = $tot_filial_cred;
            $formas[$Tr->Recebimento][$f->codigo]['Deb'] = $tot_filial_deb;
            $formas[$Tr->Recebimento][$f->codigo]['TotalVendas'] = $tot_filial_valor;
            /*echo 'Totais da Filial -->' . $tot_filial_qtde . ' - ' . $tot_filial_valor . ' Ticket Médio = ' . $ticket_medio . "</br>";
            echo "<hr>";*/
            
        }
        return view('painel.vendas.Vendas', compact('ListFiliais','Filiais','TipoRecebimentos','data1','data2','formas'));
    }
}
