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
        $data1 = '2018-04-01 00:00:00';
        $data2 = '2018-04-30 23:59:59';
        $formas = array();
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_din = 0;
        $gran_ticket = 0;

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
            $tot_filial_din = 0;
            $tot_filial_qtde_din = 0;
            foreach($TipoRecebimentos  as $Tr ) {
                $tot_pgto = 0;
                //$formas[$f->codigo][] = $Tr->Recebimento;
                $Vendas = MSicTabEst3A::where('LkReceb',$Tr->Controle)
                                        ->orderBy('LkReceb')
                                        ->where('filial_id',$f->id)
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
                switch ($Tr->tipo) {
                    case 'C':
                        $tot_filial_cred = $tot_filial_cred + $tot_pgto;
                        $tot_filial_qtde_cred = $tot_filial_qtde_cred + 1;
                        break;
                    case 'D':
                        $tot_filial_deb = $tot_filial_deb + $tot_pgto; 
                        $tot_filial_qtde_deb = $tot_filial_qtde_deb + 1;                        
                        break;
                    default:
                        $tot_filial_din = $tot_filial_din + $tot_pgto;
                        $tot_filial_qtde_din = $tot_filial_qtde_din + 1;
                }
            }
            if ($tot_filial_qtde > 0){
                $ticket_medio = $tot_filial_valor / $tot_filial_qtde;
            } else {
                $ticket_medio = 0;
            }
            $gran_total = $gran_total   + $tot_filial_valor;
            $gran_qtde = $gran_qtde     + $tot_filial_qtde;
            $gran_cred = $gran_cred     + $tot_filial_cred;
            $gran_deb = $gran_deb       + $tot_filial_deb;
            $gran_din = $gran_din       + $tot_filial_din;
        
            $formas[$Tr->Recebimento][$f->codigo]['Qtde_Vendas'] = $tot_filial_qtde;
            $formas[$Tr->Recebimento][$f->codigo]['TicketM'] = $ticket_medio;
            $formas[$Tr->Recebimento][$f->codigo]['Din'] = $tot_filial_din;
            $formas[$Tr->Recebimento][$f->codigo]['Cred'] = $tot_filial_cred;
            $formas[$Tr->Recebimento][$f->codigo]['Deb'] = $tot_filial_deb;
            $formas[$Tr->Recebimento][$f->codigo]['TotalVendas'] = $tot_filial_valor;
            
            /*echo 'Totais da Filial -->' . $tot_filial_qtde . ' - ' . $tot_filial_valor . ' Ticket Médio = ' . $ticket_medio . "</br>";
            echo "<hr>";*/
        }
        $formas['GranTotalVendas'] = $gran_total;
        $formas['GranTotalQtde'] = $gran_qtde;
        $formas['GranTotalCred'] = $gran_cred;
        $formas['GranTotalDin'] = $gran_din;
        $formas['GranTotalDeb'] = $gran_deb;
        return view('painel.vendas.Vendas', compact('ListFiliais','Filiais','TipoRecebimentos','data1','data2','formas'));
    }

    public function ranking_vendas()
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->get();
        $ListFiliais        = $Filiais;
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        //$data1 = $request->initial_date . ' 00:00:00';
        //$data2 = $request->final_date   . ' 23:59:59';
        $data1 = '2018-04-01 00:00:00';
        $data2 = '2018-04-30 23:59:59';
        $formas = array();
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_din = 0;
        $gran_ticket = 0;

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
                                        ->where('filial_id',$f->id)
                                        ->where('Cancelada','0')
                                        ->where('LkTipo','2')
                                        ->wherebetween('Data',[$data1,$data2])
                                        ->with('prodVendidos')
                                        ->orderBy('LkReceb')
                                        ->get();
                $tot_qtde_receb = $Vendas->count();
                if(count($Vendas)>0){
                    $tot_vendas = 0;
                    foreach($Vendas as $V){
                        $tot_vendas = $tot_vendas + $V->prodVendidos->sum('Total');
                    }
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => $tot_pgto) ;
                    $tot_filial_qtde = $tot_filial_qtde + $tot_qtde_receb;
                    $tot_filial_valor = $tot_filial_valor + $tot_pgto; 
                }else{
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => 0) ;
                }
                switch ($v->Receb->tipo) {
                    case 'C':
                        $tot_filial_cred = $tot_filial_cred + $tot_pgto;
                        $tot_filial_qtde_cred = $tot_filial_qtde_cred + 1;
                        break;
                    case 'D':
                        $tot_filial_deb = $tot_filial_deb + $tot_pgto; 
                        $tot_filial_qtde_deb = $tot_filial_qtde_deb + 1;                        
                        break;
                    default:
                        $tot_filial_din = $tot_filial_deb + $tot_pgto;
                }
            }
            if ($tot_filial_qtde > 0){
                $ticket_medio = $tot_filial_valor / $tot_filial_qtde;
            } else {
                $ticket_medio = 0;
            }
            $gran_total = $gran_total + $tot_filial_valor;
            $gran_qtde = $gran_qtde + $tot_filial_qtde;
            $gran_cred = $gran_cred + $tot_filial_cred;
            $gran_deb = $gran_deb + $tot_filial_deb;
        
            $formas[$Tr->Recebimento][$f->codigo]['Qtde_Vendas']    = $tot_filial_qtde;
            $formas[$Tr->Recebimento][$f->codigo]['TicketM']        = $ticket_medio;
            $formas[$Tr->Recebimento][$f->codigo]['Cred']           = $tot_filial_cred;
            $formas[$Tr->Recebimento][$f->codigo]['Deb']            = $tot_filial_deb;
            $formas[$Tr->Recebimento][$f->codigo]['Din']            = $tot_filial_din;
            $formas[$Tr->Recebimento][$f->codigo]['TotalVendas']    = $tot_filial_valor;
            
            /*echo 'Totais da Filial -->' . $tot_filial_qtde . ' - ' . $tot_filial_valor . ' Ticket Médio = ' . $ticket_medio . "</br>";
            echo "<hr>";*/
        }
        $formas['GranTotalVendas']  = $gran_total;
        $formas['GranTotalQtde']    = $gran_qtde;
        $formas['GranTotalCred']    = $gran_cred;
        $formas['GranTotalDeb']     = $gran_deb;
        return view('painel.vendas.ranking', compact('ListFiliais','Filiais','TipoRecebimentos','data1','data2','formas'));
    }
    public function ranking_vendedores()
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->get();
        $ListFiliais        = $Filiais;
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        $listVend           = MSicTabVend::get(['id','Controle','Nome','Comissao', 'DataInc']);
        //$data1 = $request->initial_date . ' 00:00:00';
        //$data2 = $request->final_date   . ' 23:59:59';
        $data1 = '2018-04-01 00:00:00';
        $data2 = '2018-04-30 23:59:59';
        $formas = array();
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_ticket = 0;
        $formas = array();
        foreach ($Filiais as $f) {
            foreach ($listVend as $lV) {
                $Vendas = MSicTabEst3A::where('LkVendedor',$lV->Controle)
                ->where('filial_id',$f->id)
                ->where('Cancelada','0')
                ->where('LkTipo','2')
                ->wherebetween('Data',[$data1,$data2])
                ->with(['prodVendidos','vendedor','Receb'])
                ->orderBy('LkVendedor')
                ->get();
                $tot_vendas_vendedor = 0;
                $tot_qtde_vendas_vendedor = $Vendas->count();
                if (count($Vendas) > 0) {
                    $tot_qtde_vendas_vendedor = $Vendas->count();
                    $tot_valor_vendas_vendedor = 0;
                    $tot_valor_com_vendedor = 0;
                    $tot_valor_vendas_cred = 0;
                    $tot_valor_vendas_deb = 0;
                    $tot_valor_vendas_din = 0;
                    $ticket_vendedor = 0;
                    foreach ($Vendas as $v) {
                        $tot_valor_vendas_vendedor = $tot_valor_vendas_vendedor + $v->prodVendidos->sum('Total');
                        
                        if ($v->Receb()->count() > 0) {
                            switch ($v->Receb->tipo) {
                                case 'C':
                                    $tot_valor_vendas_cred = $tot_valor_vendas_cred + $v->prodVendidos->sum('Total');
                                    break;
                                case 'D':
                                    $tot_valor_vendas_deb = $tot_valor_vendas_deb + $v->prodVendidos->sum('Total');
                                    break;
                                default:
                                    $tot_valor_vendas_din = $tot_valor_vendas_din + $v->prodVendidos->sum('Total');
                            }
                        }
                    }
                    $tot_valor_com_vendedor = (($v->vendedor->Comissao / 100) * $tot_valor_vendas_vendedor);
                    $tot_valor_com_vendedor = number_format(($tot_valor_com_vendedor),2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Valor'] = number_format($tot_valor_vendas_vendedor,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Qtde'] = $tot_qtde_vendas_vendedor;
                    $formas[$f->fantasia][$lV->Nome]['TicketM'] = number_format(($tot_valor_vendas_vendedor / $tot_qtde_vendas_vendedor),2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Cred'] = number_format($tot_valor_vendas_cred,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Deb'] = number_format($tot_valor_vendas_deb,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Din'] = number_format($tot_valor_vendas_din,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Comissao'] = $tot_valor_com_vendedor;
                }
            }
        }
/*        foreach ($formas as $filial => $vendedores) {
            echo $filial . "</br>";
                foreach($vendedores as $nomes => $valores) {
                    echo $nomes . "</br>";
                    foreach($valores as $tipos => $valor) {
                        echo "<ul>" . $tipos  . " -> " . $valor . "</ul>" . "</br>";
                    }
                }
            echo "<hr>";
        }*/
        return view('painel.vendas.ranking_vendedor', compact('ListFiliais','Filiais','TipoRecebimentos','data1','data2','formas'));
    }
}