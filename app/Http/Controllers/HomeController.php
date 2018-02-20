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

}
