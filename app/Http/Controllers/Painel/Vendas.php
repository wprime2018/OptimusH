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

    public function ranking_vendedores(Request $request)
    {
        $Filiais = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        if (isset($request->filial_id)) {
            $filial_change = Filiais::where('id',$request->filial_id)->get(['fantasia']);
            foreach ($filial_change as $f) {
                $filial_changed = $f->fantasia;
            }
            $periodo = Carbon::createFromFormat('Y-m-d',$request->initial_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d',$request->final_date)->format('d/m/Y');
            $data1 = Carbon::createFromFormat('Y-m-d',$request->initial_date)->startOfDay()->toDateTimeString();
            $data2 = Carbon::createFromFormat('Y-m-d',$request->final_date)->endOfDay()->toDateTimeString();

            $dados = FunctionsController::calcula_comissao($request->filial_id, 
                                                            $data1, 
                                                            $data2, 
                                                            $request->porcComissaoChip);
            return view('painel.vendas.ranking_vendedor', compact('Filiais', 'dados', 'filial_changed', 'periodo'));
        } else {
            return view('painel.vendas.ranking_vendedor', compact('Filiais'));
        }
    }
    
    public function ranking_diario(Request $request)
    {
        $minMaxVendas = FunctionsController::vendasMinMaxData();
        
        if (isset($request->month_date)) {

            $Filiais = Filiais::filial_NCD();
            $dados = FunctionsController::ranking_diario($request->month_date);
            $dados2 = json_encode($dados);
            return view('painel.vendas.ranking_diario', compact('minMaxVendas','dados', 'Filiais', 'dados2'));
        
        } else {
        
            return view('painel.vendas.ranking_diario', compact('minMaxVendas'));
        
        }
    }

    public function nfce(Request $request) {

        $ListFiliais        = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();

        if (isset($request->filial_id)) {
            $periodo = Carbon::createFromFormat('Y-m-d',$request->initial_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d',$request->final_date)->format('d/m/Y');
            $data1 = Carbon::createFromFormat('Y-m-d',$request->initial_date)->startOfDay()->toDateTimeString();
            $data2 = Carbon::createFromFormat('Y-m-d',$request->final_date)->endOfDay()->toDateTimeString();

            $dados = FunctionsController::calcula_nfce($request->filial_id, $data1, $data2);
            return view('painel.vendas.nfce', compact('ListFiliais', 'dados'));
        } else {
            return view('painel.vendas.nfce', compact('ListFiliais', 'filial_changed'));
        }
    }
}   
