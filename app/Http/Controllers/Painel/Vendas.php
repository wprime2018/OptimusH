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
        $Filiais = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();

        if (isset($request->initial_date)) {

            $periodo = Carbon::createFromFormat('Y-m-d',$request->initial_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d',$request->final_date)->format('d/m/Y');
            $initial_date = Carbon::createFromFormat('Y-m-d',$request->initial_date)->startOfDay()->toDateTimeString();
            $final_date = Carbon::createFromFormat('Y-m-d',$request->final_date)->endOfDay()->toDateTimeString();
            $dados = FunctionsController::vendas_pgto($initial_date, $final_date);
            return view('painel.vendas.Vendas', compact('Filiais', 'dados', 'periodo'));

        } else {

            return view('painel.vendas.Vendas', compact('Filiais'));

        }

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
        $Filiais = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        if (isset($request->filial_id)) {
            $filial_change = Filiais::where('id',$request->filial_id)->get(['fantasia']);
            foreach ($filial_change as $f) {
                $filial_changed = $f->fantasia;
            }
            $periodo = Carbon::createFromFormat('Y-m-d',$request->initial_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d',$request->final_date)->format('d/m/Y');

            $dados = FunctionsController::calcula_comissao($request->filial_id, 
                                                            $request->initial_date, 
                                                            $request->final_date, 
                                                            $request->porcComissaoChip);
            return view('painel.vendas.ranking_vendedor', compact('Filiais', 'dados', 'filial_changed', 'periodo'));
        } else {
            return view('painel.vendas.ranking_vendedor', compact('Filiais'));
        }
    }
    
    public function ranking_diario(Request $request)
    {
        $minMaxVendas = functionscontroller::vendasMinMaxData();
        
        if (isset($request->month_date)) {
            $Filiais = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
            $dados = FunctionsController::ranking_diario($request->month_date);
            return view('painel.vendas.ranking_diario', compact('minMaxVendas','dados', 'Filiais'));
        
        } else {
        
            return view('painel.vendas.ranking_diario', compact('minMaxVendas'));
        
        }
    }

    public function nfce(Request $request) {

        $ListFiliais        = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();

        if (isset($request->filial_id)) {
            //$periodo = Carbon::createFromFormat('Y-m-d',$request->initial_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d',$request->final_date)->format('d/m/Y');
            $data1 = Carbon::createFromFormat('Y-m-d',$request->initial_date)->startOfDay()->toDateTimeString();
            $data2 = Carbon::createFromFormat('Y-m-d',$request->final_date)->endOfDay()->toDateTimeString();

            $dados = FunctionsController::calcula_nfce($request->filial_id, $data1, $data2);
            return view('painel.vendas.nfce', compact('ListFiliais', 'dados'));
        } else {
            return view('painel.vendas.nfce', compact('ListFiliais', 'filial_changed'));
        }
    }
}   
