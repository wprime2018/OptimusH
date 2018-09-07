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
use App\Models\Painel\Comissao;
use App\Models\Painel\MSicTabNFCe;
use App\Http\Controllers\Painel\FunctionsController;
class Vendas extends Controller
{
    public function index_vendas_pgto(Request $request)
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $ListFiliais        = $Filiais;
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        //$data1 = $request->initial_date . ' 00:00:00';
        //$data2 = $request->final_date   . ' 23:59:59';
        if (isset($request)) {
            
            if (empty($request->initial_date))
                $data1 = Carbon::now()->startOfDay();
            else 
                $data1 = $request->initial_date . ' 00:00:00';
                $data1 = new Carbon($data1);
                
            if (empty($request->final_date))   
                $data2 = Carbon::now()->endOfDay();
            else 
                $data2 = $request->final_date   . ' 23:59:59';
                $data2 = new Carbon($data2);
                
        } else {
            $data1 = Carbon::now()->firstOfMonth()->startOfDay();
            $data2 = Carbon::now()->lastOfMonth()->endOfDay();
        }
        $diaData1 = $data1->day;
        $diaData2 = $data2->day;
        $formas = array();
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_din = 0;
        $gran_ticket = 0;
        $gran_qNfce = 0;
        $gran_vNfce = 0;

        foreach ($Filiais as $f) {
            $tot_filial_qtde = 0;
            $tot_filial_valor = 0;
            $tot_filial_cred = 0;
            $tot_filial_qtde_cred = 0;
            $tot_filial_deb = 0;
            $tot_filial_qtde_deb = 0;
            $tot_filial_din = 0;
            $tot_filial_qtde_din = 0;
            $tot_filial_qtde_nfce = 0;
            $tot_filial_valor_nfce = 0;
            foreach($TipoRecebimentos  as $Tr ) {
                $tot_pgto = 0;
                //$formas[$f->codigo][] = $Tr->Recebimento;
                $dt1 = $data1->toDateTimeString();
                $dt2 = $data2->toDateTimeString();
                $Vendas = MSicTabEst3A::where('LkReceb',$Tr->Controle)
                                        ->where('filial_id',$f->id)
                                        ->where('Cancelada','0')
                                        ->where('LkTipo','2')
                                        ->wherebetween('Data',[$dt1,$dt2])
                                        ->with(['prodVendidos','vendedor','Receb'])
                                        ->orderBy('LkReceb')
                                        ->get();
                $tot_qtde_receb = $Vendas->count();
                if(count($Vendas)>0){
                    foreach($Vendas as $V){
                        $tot_pgto += $V->prodVendidos->sum('Total');
                    }
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => $tot_pgto) ;
                    $tot_filial_qtde  += $tot_qtde_receb;
                    $tot_filial_valor += $tot_pgto; 
                    if (!is_null($V->Nota)) {
                        $tot_filial_valor_nfce += $tot_pgto;
                        ++$tot_filial_qtde_nfce;  
                    }
                }else{
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => 0) ;
                }
                switch ($Tr->tipo) {
                    case 'C':
                        $tot_filial_cred += $tot_pgto;
                        ++$tot_filial_qtde_cred;
                        break;
                    case 'D':
                        $tot_filial_deb += $tot_pgto; 
                        ++$tot_filial_qtde_deb;                        
                        break;
                    default:
                        $tot_filial_din += $tot_pgto;
                        ++$tot_filial_qtde_din;
                }
            }
            if ($tot_filial_qtde > 0)
                $ticket_medio = $tot_filial_valor / $tot_filial_qtde;
            else 
                $ticket_medio = 0;
            
            $gran_total += $tot_filial_valor;
            $gran_qtde  += $tot_filial_qtde;
            $gran_cred  += $tot_filial_cred;
            $gran_deb   += $tot_filial_deb;
            $gran_din   += $tot_filial_din;
            $gran_qNfce += $tot_filial_qtde_nfce;
            $gran_vNfce += $tot_filial_valor_nfce;
        
            $formas[$Tr->Recebimento][$f->codigo]['Qtde_Vendas'] = $tot_filial_qtde;
            $formas[$Tr->Recebimento][$f->codigo]['TicketM']     = $ticket_medio;
            $formas[$Tr->Recebimento][$f->codigo]['Din']         = $tot_filial_din;
            $formas[$Tr->Recebimento][$f->codigo]['Cred']        = $tot_filial_cred;
            $formas[$Tr->Recebimento][$f->codigo]['Deb']         = $tot_filial_deb;
            $formas[$Tr->Recebimento][$f->codigo]['TotalVendas'] = $tot_filial_valor;
            $formas[$Tr->Recebimento][$f->codigo]['TotalNfce']   = $tot_filial_valor_nfce;
            $formas[$Tr->Recebimento][$f->codigo]['QtdeNfce']    = $tot_filial_qtde_nfce;
            
            /*echo 'Totais da Filial -->' . $tot_filial_qtde . ' - ' . $tot_filial_valor . ' Ticket Médio = ' . $ticket_medio . "</br>";
            echo "<hr>";*/
        }
        $formas['GranTotalVendas'] = $gran_total;
        $formas['GranTotalQtde'] = $gran_qtde;
        $formas['GranTotalCred'] = $gran_cred;
        $formas['GranTotalDin'] = $gran_din;
        $formas['GranTotalDeb'] = $gran_deb;
        $formas['GranTotalQtdeNfce'] = $gran_qNfce;
        $formas['GranTotalNfce'] = $gran_vNfce;
        return view('painel.vendas.Vendas', compact('ListFiliais','Filiais','TipoRecebimentos','data1','data2','formas'));
    }

    public function ranking_vendas()
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
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
                        $tot_vendas += $V->prodVendidos->sum('Total');
                    }
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => $tot_pgto) ;
                    $tot_filial_qtde += $tot_qtde_receb;
                    $tot_filial_valor += $tot_pgto; 
                }else{
                    $formas[$Tr->Recebimento][$f->codigo] = Array ('Qtde' => $tot_qtde_receb, 'Total' => 0) ;
                }
                switch ($v->Receb->tipo) {
                    case 'C':
                        $tot_filial_cred += $tot_pgto;
                        ++$tot_filial_qtde_cred;
                        break;
                    case 'D':
                        $tot_filial_deb += $tot_pgto; 
                        ++$tot_filial_qtde_deb;                        
                        break;
                    default:
                        $tot_filial_din += $tot_pgto;
                }
            }
            if ($tot_filial_qtde > 0){
                $ticket_medio = $tot_filial_valor / $tot_filial_qtde;
            } else {
                $ticket_medio = 0;
            }
            $gran_total += $tot_filial_valor;
            $gran_qtde  += $tot_filial_qtde;
            $gran_cred  += $tot_filial_cred;
            $gran_deb   += $tot_filial_deb;
        
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
    public function ranking_vendedores(Request $request)
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $ListFiliais        = $Filiais;
        if (isset($request->filial_id)) {
            $dados = FunctionsController::calcula_comissao($request->filial_id, 
                                                            $request->initial_date, 
                                                            $request->final_date, 
                                                            $request->porcComissaoChip);
            return view('painel.vendas.ranking_vendedor', compact('ListFiliais', 'dados'));
        } else {
            return view('painel.vendas.ranking_vendedor', compact('ListFiliais'));
        }
    }
    public function ranking_diario(Request $request)
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $ListFiliais        = $Filiais;
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        if(isset($request)) {
            $data1 = Carbon::create($request->year_date, $request->month_date, 10, 0, 0, 0)->firstOfMonth()->startOfDay();
            $data2 = Carbon::create($request->year_date, $request->month_date, 10, 0, 0, 0)->lastOfMonth()->endOfDay();
            $diaData1 = $data1->day;
            $diaData2 = $data2->day; 
        } else {
            $data1 = Carbon::now()->firstOfMonth()->startOfDay();
            $data2 = Carbon::now()->lastOfMonth()->endOfDay();
        }
        $formas = array();
        $gran_total = array();
        $dataInicial = $data1->day(1);
        $dataFinal = $data2->day(1);
        for ($i = $diaData1; $i <= $diaData2; $i++) {
            
            $diadaSemana = $dataInicial->format('D');
            switch ($diadaSemana) {
                case 'Sun':
                    $diadaSemana = 'Dom';
                    break;
                case 'Mon':
                    $diadaSemana = 'Seg';
                    break;
                case 'Tue':
                    $diadaSemana = 'Ter';
                    break;
                case 'Wed':
                    $diadaSemana = 'Qua';
                    break;
                case 'Thu':
                    $diadaSemana = 'Qui';
                    break;
                case 'Fri':
                    $diadaSemana = 'Sex';
                    break;
                case 'Sat':
                    $diadaSemana = 'Sáb';
                    break;
            }
            $dt1 = $data1->toDateTimeString();
            $dt2 = $data2->toDateTimeString();
            $dataFormat = $dataInicial->format('d/m/Y') . " ". $diadaSemana;
            foreach($Filiais as $f) {
                $Vendas = MSicTabEst3A::where('filial_id',$f->id)
                                        ->where('Cancelada','0')
                                        ->where('LkTipo','2')
                                        ->wherebetween('Data',[$dt1,$dt2])
                                        ->with(['prodVendidos','vendedor','Receb'])
                                        ->orderBy('LkReceb')
                                        ->get();
                $qtde_vendas_dia = $Vendas->count();
                $tot_valor_vendas_dia = 0;
                $tot_valor_vendas_diaCred = 0;
                $tot_valor_vendas_diaDeb = 0;
                $tot_valor_vendas_diaDin = 0;
                if ($qtde_vendas_dia > 0) {
                    foreach ($Vendas as $v) {
                        $tot_valor_vendas_dia += $v->prodVendidos->sum('Total');
                        if ($v->Receb()->count() > 0) {
                            switch ($v->Receb->tipo) {
                                case 'C':
                                    $tot_valor_vendas_diaCred += $v->prodVendidos->sum('Total');
                                    break;
                                case 'D':
                                    $tot_valor_vendas_diaDeb += $v->prodVendidos->sum('Total');
                                    break;
                                default:
                                    $tot_valor_vendas_diaDin += $v->prodVendidos->sum('Total');
                            }
                        }
                        $dataFormat = $dataInicial->format('d/m/Y') . " ". $diadaSemana;
                        $formas[$dataFormat][$f->codigo]['Total'] = $tot_valor_vendas_dia;
                        $formas[$dataFormat][$f->codigo]['Qtde']  = $qtde_vendas_dia;
                        $formas[$dataFormat][$f->codigo]['Cred']  = $tot_valor_vendas_diaCred;
                        $formas[$dataFormat][$f->codigo]['Deb']   = $tot_valor_vendas_diaDeb;
                        $formas[$dataFormat][$f->codigo]['Din']   = $tot_valor_vendas_diaDin;
                 }
                } else {
                    $formas[$dataFormat][$f->codigo]['Total'] = 0;
                    $formas[$dataFormat][$f->codigo]['Qtde']  = 0;
                    $formas[$dataFormat][$f->codigo]['Cred']  = 0;
                    $formas[$dataFormat][$f->codigo]['Deb']   = 0;
                    $formas[$dataFormat][$f->codigo]['Din']   = 0;
                }
                
           //Calculando vendas Canceladas 
                $Vendas = MSicTabEst3A::where('filial_id',$f->id)
                                        ->where('Cancelada','1')
                                        ->where('LkTipo','2')
                                        ->wherebetween('Data',[$dt1,$dt2])
                                        ->with(['prodVendidos','vendedor','Receb'])
                                        ->orderBy('LkReceb')
                                        ->get();
                $qtde_vendas_dia = $Vendas->count();
                $tot_valor_vendas_dia = 0;
                if ($qtde_vendas_dia > 0) {
                    foreach ($Vendas as $v) {
                        $tot_valor_vendas_dia += $v->prodVendidos->sum('Total');
                        $formas[$dataFormat][$f->codigo]['Total_Canc'] = $tot_valor_vendas_dia;
                        $formas[$dataFormat][$f->codigo]['Qtde_Canc'] = $qtde_vendas_dia; 
                    }
                }
            }
            
            $dataInicial = $data1->day($i);
            $dataFinal = $data2->day($i);
        }
        $message = "Período informado de ". $data1 . "até" . $data2;
        $soma = array();
        foreach($formas as $i => $value1) {
            foreach($value1 as $j => $value2){
                if (isset($soma[$j]['Total'])) {
                    $soma[$j]['Total'] = $soma[$j]['Total'] + $value2['Total'];
                } else {
                    $soma[$j]['Total'] = $value2['Total'];
                }
            }
        }            
        return view('painel.vendas.ranking_diario', compact('ListFiliais','Filiais','data1','data2','formas','soma'));
    }

    public function nfce(Request $request) {

        $ListFiliais        = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $filial_changed     = '';
        if (isset($request->filial_id)) {
            $dados = FunctionsController::calcula_nfce($request->filial_id, $request->initial_date, $request->final_date);
            return view('painel.vendas.nfce', compact('ListFiliais', 'dados'));
        } else {
            return view('painel.vendas.nfce', compact('ListFiliais', 'filial_changed'));
        }
    }
}   
