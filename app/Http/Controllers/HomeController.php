<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Painel\Despesas;
use App\Models\Painel\Filiais;
use App\Models\Painel\TpDespesas;
use App\Models\Painel\MSicTabEst1;
use App\Models\Painel\MSicTabEst7;
use App\Models\Painel\MSicTabEst3A;
use App\Models\Painel\MSicTabVend;
use App\Models\Painel\Estoque;
use Illuminate\Support\Facades\View;
use DB;
use Carbon\Carbon;
class HomeController extends Controller
{
    public $lblTotDespesa;
    public $lblTotFilial;
    public $lblTotTpDespesa;
    
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lblTotProdutos     = MSicTabEst1::count();
        $lblTotDespesa     = Despesas::count();
        $lblTotFilial      = Filiais::where('ativo', '=', 1)->count();
        $lblTotTpDespesa   = TpDespesas::count();

        /*
        *   Começando a quantidade de Pedidos dos produtos
        */
        $countProdDanger    = Estoque::distinct()->where('Status',"Urgente!")->count(['LkProduto']);
        $sumProdDanger      = Estoque::where('Status',"Urgente!")->sum('Comprar');
        if ($lblTotProdutos > 0) {
            $porcProdDanger     = round(($countProdDanger / $lblTotProdutos) * 100,0);
        } else { 
            $porcProdDanger = 0;
        }
        $fatiaProdDanger    = round(((2600 * $porcProdDanger) / 100),0);

        $countProdWarning   = Estoque::where('Status',"Atenção")->count();
        $sumProdWarning     = Estoque::where('Status',"Atenção")->sum('Comprar');
        if ($lblTotProdutos > 0 ){
            $porcProdWarning     = round(($countProdWarning / $lblTotProdutos) * 100,0);
        } else {
            $porcProdWarning = 0;
        }
        
        $fatiaProdWarning    = round(((2600 * $porcProdWarning) / 100),0);

        $countProdSuccess   = Estoque::where('Status',"Não comprar")->count();
        $countProdNoVend    = Estoque::distinct()
                                    ->wherenull('Vendidos')
                                    ->where('Atual','>',0)
                                    ->count(['LkProduto']);
        $sumProdNoVend      = Estoque::where('Atual','>',0)
                                    ->wherenull('Vendidos')
                                    ->select('LkProduto','filial_id')
                                    ->distinct('LkProduto')
                                    ->sum('Atual');
        if ($lblTotProdutos > 0)
            $porcProdNoVend     = round(($countProdNoVend / $lblTotProdutos) * 100,0);
        else 
            $porcProdNoVend = 0;
                
        $fatiaProdNoVend    = round(((2600 * $porcProdNoVend) / 100),0);
        
        $fatiaRestante      = round((2600 - $fatiaProdDanger - $fatiaProdWarning - $fatiaProdNoVend),0);
        
        return view('home',compact('lblTotDespesa', 
                                    'lblTotFilial', 
                                    'lblTotTpDespesa',
                                    'lblTotProdutos',
                                    'countProdDanger',
                                    'sumProdDanger',
                                    'countProdWarning',
                                    'sumProdWarning',
                                    'countProdSuccess',
                                    'countProdNoVend',
                                    'sumProdNoVend',
                                    'fatiaProdWarning',
                                    'fatiaProdDanger',
                                    'fatiaProdNoVend',
                                    'fatiaRestante',
                                    'porcProdDanger',
                                    'porcProdWarning',
                                    'porcProdNoVend'
                                    ));

    }
    public function index_Produtos()
    {
        
        $lblTotFilial       = MSicTabEst1::where('ativo', '=', 1)->count();
        $Filiais            = Filiais::where('ativo', '=', 1)->get();
        $TipoRecebimentos   = MSicTabEst7::get();
        $totRecebPorFilial = array();        
        foreach ($Filiais as $c) {
            $idAgora = $c->id;
            
            $Vendas = MSicTabEst3A::join('m_sic_tab_est7s', 'm_sic_tab_est3_as.LkReceb', '=', 'm_sic_tab_est7s.Controle')
                                ->join('tb_filiais', 'm_sic_tab_est3_as.filial_id', '=', 'tb_filiais.id')
                                ->join('m_sic_tab_vends', 'm_sic_tab_est3_as.LkVendedor', '=', 'm_sic_tab_vends.Controle')
                                ->join('m_sic_tab_est3_bs', 'm_sic_tab_est3_as.Controle', '=', 'm_sic_tab_est3_bs.LkEst3A')
                                ->select(DB::raw('sum(m_sic_tab_est3_bs.Total) AS Total_Recebido'),'m_sic_tab_est3_as.*', 'tb_filiais.fantasia','m_sic_tab_vends.Nome','m_sic_tab_est7s.Recebimento')        
                                ->orderBy('Recebimento')
                                ->with('prodVendidos')
                                ->where('m_sic_tab_est3_as.filial_id',$idAgora)
                                ->where('m_sic_tab_est3_as.cancelada','0')
                                ->groupby('m_sic_tab_est3_bs.LkEst3A')
                                ->get();
            
            foreach ($Vendas as $d) {
                $key = $d->Recebimento;
                $filial = $c->id;

                if (!isset($totRecebPorFilial[$filial][$key])) {

                    $totRecebPorFilial[$filial][$key] = $d->Total_Recebido;
                    
                } else {

                    $totRecebPorFilial[$filial][$key] = $totRecebPorFilial[$filial][$key] + $d->Total_Recebido;

                }
            }
        }
        return view('painel.dashboard.DashFiliais',compact('lblTotFilial', 'Filiais','totRecebPorFilial','TipoRecebimentos'));
    }
    public function dashboard_filiais(Request $request)
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        $listVend           = MSicTabVend::get(['id','Controle','Nome','Comissao', 'DataInc']);
        $prod               = MSicTabEst1::where('Codigo','000323')->get();
        foreach ($prod as $chip) {

        }
        if (isset($request)) {
            $data1 = $request->initial_date . ' 00:00:00';
            $data2 = $request->final_date   . ' 23:59:59';
        } else {
            $data1 = Carbon::now(-30);
            $data2 = Carbon::now();
            $data1 = $data1->toDateTimeString();
            $data2 = $data2->toDateTimeString();
            echo var_dump($data2);
        }
        $carbonData1 = new Carbon($data1);
        $carbonData2 = new Carbon($data2);
        $periodo = 'De' . $carbonData1->format('d/m/Y') . ' até ' . $carbonData2->format('d/m/Y'). '.'; 
        $diaData1 = $carbonData1->day;
        $diaData2 = $carbonData2->day;
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_ticket = 0;
        $formas = array();
        $totais = array();
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
                        $tot_valor_vendas_vendedor += $v->prodVendidos->sum('Total');
                        
                        if ($v->Receb()->count() > 0) {
                            switch ($v->Receb->tipo) {
                                case 'C':
                                    $tot_valor_vendas_cred += $v->prodVendidos->sum('Total');
                                    break;
                                case 'D':
                                    $tot_valor_vendas_deb += $v->prodVendidos->sum('Total');
                                    break;
                                default:
                                    $tot_valor_vendas_din += $v->prodVendidos->sum('Total');
                            }
                        }
                        // Calculando os Chips Vendidos
                        if ($v->prodVendidos()->count() > 0) {
                            foreach($v->prodVendidos as $vPv) {
                                $prod2 = $vPv->LkProduto;
                                if  ($prod2 == $chip->Controle) {
                                    if (isset($formas[$f->fantasia][$lV->Nome]['CHIP']['Total'])) {
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['Total']         += $vPv->Total;
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['TotVenda']      += $vPv->TotVenda;
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['Quantidade']    += $vPv->Quantidade;
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['TotalPagar']    += ($vPv->Total * ($request->porcComissaoChip /100));
                                    } else {
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['Total']         = $vPv->Total;
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['TotVenda']      = $vPv->TotVenda;
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['Quantidade']    = $vPv->Quantidade;
                                        $formas[$f->fantasia][$lV->Nome]['CHIP']['TotalPagar']    = $vPv->Total * ($request->porcComissaoChip /100);
                                    }
                                }
                            }
                        }
                    }
                    $tot_valor_com_vendedor = (($v->vendedor->Comissao / 100) * $tot_valor_vendas_vendedor);
                    if (isset($formas[$f->fantasia][$lV->Nome]['CHIP']))
                        $formas[$f->fantasia][$lV->Nome]['TotalPagar'] = $tot_valor_com_vendedor + $formas[$f->fantasia][$lV->Nome]['CHIP']['TotalPagar'];
                    else 
                        $formas[$f->fantasia][$lV->Nome]['TotalPagar'] = $tot_valor_com_vendedor;

                    $tot_valor_com_vendedor = number_format(($tot_valor_com_vendedor),2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Valor'] = number_format($tot_valor_vendas_vendedor,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Qtde'] = $tot_qtde_vendas_vendedor;
                    $formas[$f->fantasia][$lV->Nome]['TicketM'] = number_format(($tot_valor_vendas_vendedor / $tot_qtde_vendas_vendedor),2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Cred'] = number_format($tot_valor_vendas_cred,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Deb'] = number_format($tot_valor_vendas_deb,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Din'] = number_format($tot_valor_vendas_din,2,',','.');
                    $formas[$f->fantasia][$lV->Nome]['Comissao'] = $tot_valor_com_vendedor;
                    
                    if (isset($totais[$f->fantasia]['TotalVendas'])) {
                        $totais[$f->fantasia]['TotalVendas']        += $tot_valor_vendas_vendedor;
                        $totais[$f->fantasia]['TotalQtdeVendas']    += $tot_qtde_vendas_vendedor;
                        $totais[$f->fantasia]['TotalVendasCred']    += $tot_valor_vendas_cred;
                        $totais[$f->fantasia]['TotalVendasDeb']     += $tot_valor_vendas_deb;
                        $totais[$f->fantasia]['TotalVendasDin']     += $tot_valor_vendas_din;
                    }else {
                        $totais[$f->fantasia]['TotalVendas']     = $tot_valor_vendas_vendedor;
                        $totais[$f->fantasia]['TotalQtdeVendas'] = $tot_qtde_vendas_vendedor;
                        $totais[$f->fantasia]['TotalVendasCred'] = $tot_valor_vendas_cred;
                        $totais[$f->fantasia]['TotalVendasDeb']  = $tot_valor_vendas_deb;
                        $totais[$f->fantasia]['TotalVendasDin']  = $tot_valor_vendas_din;
                    } 
                }
            }
            foreach ($totais as $key => $row) {
                $filiais[$key]  = $row['TotalVendas'];
                //$edition[$key] = $row['edition'];
            }
            array_multisort($filiais, SORT_DESC, SORT_NUMERIC);
        }
        return view('painel.dashboard.DashFiliais',compact('filiais','periodo','totais'));
    }

    public function VendasTotais($initial_date, $final_date)
    {
        $Filiais            = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);

        if (isset($request)) {
            
            if (empty($initial_date))
                $data1 = Carbon::now()->startOfDay();
            else 
                $data1 = $initial_date . ' 00:00:00';
                $data1 = new Carbon($data1);
                
            if (empty($final_date))   
                $data2 = Carbon::now()->endOfDay();
            else 
                $data2 = $final_date   . ' 23:59:59';
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
                    if ($V->TipoDoc == 'NF') {
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
        return $formas;
    }
}