<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Painel\Despesas;
use App\Models\Painel\Filiais;
use App\Models\Painel\TpDespesas;
use App\Models\Painel\MSicTabEst1;
use App\Models\Painel\MSicTabEst7;
use App\Models\Painel\MSicTabEst3A;
use App\Models\Painel\Estoque;
use Illuminate\Support\Facades\View;
use DB;
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
    
    public function index_Filiais()
    {
        
        $lblTotFilial       = Filiais::where('ativo', '=', 1)->count();
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
    public function dashboard_filiais(Requesst $request)
    {
        
        $Filiais            = Filiais::where('ativo', '=', 1)->get();
        $ListFiliais        = $Filiais;
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        $listVend           = MSicTabVend::get(['id','Controle','Nome','Comissao', 'DataInc']);
        $prod               = MSicTabEst1::where('Codigo','000323')->get();
        foreach ($prod as $chip) {

        }
        //$data1 = $request->initial_date . ' 00:00:00';
        //$data2 = $request->final_date   . ' 23:59:59';
//      $data1 = '2018-04-01 00:00:00';
//      $data2 = '2018-04-30 23:59:59';
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
        $diaData1 = $carbonData1->day;
        $diaData2 = $carbonData2->day;
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
       return view('painel.dashboard.DashFiliais',compact('lblTotFilial', 'Filiais','totRecebPorFilial','TipoRecebimentos'));
    }
}