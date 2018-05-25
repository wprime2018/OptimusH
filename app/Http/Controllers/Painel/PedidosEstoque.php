<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Painel\MSicTabEst3A;
use App\Models\Painel\Estoque;
use App\Models\Painel\Filiais;
use Carbon\Carbon;
use DB;
class PedidosEstoque extends Controller
{
    public function calculaEstoque(Request $request)
    {
        $data1 = $request->initial_date . ' 00:00:00';
        $data2 = $request->final_date   . ' 23:59:59';
        $carbonData1 = new Carbon($data1);
        $carbonData2 = new Carbon($data2);
        $diaData1 = $carbonData1->day;
        $diaData2 = $carbonData2->day;
    
        $ListFiliais = Filiais::where('ativo','=', '1')->get();
        $ListFiliaisCD = Filiais::where('ativo','=', '1')->where('filial_cd','=','1')->get();
        $totalProd = array();
        foreach($ListFiliais as $Filiais) {
            $ProdVendidosPorFilial = 
                MSicTabEst3A::join('tb_filiais', 'm_sic_tab_est3_as.filial_id', '=', 'tb_filiais.id')
                            ->join('m_sic_tab_est3_bs', 'm_sic_tab_est3_as.Controle', '=', 'm_sic_tab_est3_bs.LkEst3A')
                            ->select(DB::raw('sum(m_sic_tab_est3_bs.Quantidade) AS Vendidos'),
                                            'm_sic_tab_est3_bs.LkProduto')
                            ->orderBy('Vendidos','desc')
                            ->with('prodVendidos')
                            ->where('m_sic_tab_est3_as.filial_id',$Filiais->id)
                            ->where('m_sic_tab_est3_as.cancelada','0')
                            ->where('m_sic_tab_est3_as.LkTipo','2')
                            ->wherebetween('m_sic_tab_est3_as.Data',[$data1,$data2])
                            ->groupby('m_sic_tab_est3_bs.LkProduto')
                            ->get();
            $countProdVend = count($ProdVendidosPorFilial);
            if($countProdVend > 0) {
                $qtde_dias = $diaData2 - $diaData1;
                foreach ($ProdVendidosPorFilial as $pv => $valor) {
                    $estMinimo = $valor->Vendidos;
                    $estIdeal = ($valor->Vendidos / $qtde_dias) * ($qtde_dias * 1.5);
                    $estIdeal = round($estIdeal,0);

                    $prodAtualizado = Estoque::where('filial_id', $Filiais->id)
                                            ->where('LkProduto', $valor->LkProduto)
                                            ->limit(10)
                                            ->get();
                    foreach($prodAtualizado as $pA) {
                        switch(true){
                        case ($pA->Atual > $estIdeal):
                            $Status = "Não comprar";
                            $Comprar = 0;
                            break;        
                        
                        case ($pA->Atual < $estIdeal and $pA->Atual > $estMinimo):
                            $Status = "Atenção";
                            $Comprar = $estIdeal - $pA->Atual;
                            if (count($ListFiliaisCD)>0) {
                                $estqCD = self::EstoqueAtualCD($pA->LkProduto);
                                if ($Comprar < $estqCD) {
                                    $Status = "Transferir";
                                }
                            }
                            break;
                        
                        case ($pA->Atual < $estMinimo):
                            $Status = "Urgente!";
                            $Comprar = $estIdeal - $pA->Atual;
                            if (count($ListFiliaisCD)>0) {
                                $estqCD = self::EstoqueAtualCD($pA->LkProduto);
                                if ($Comprar < $estqCD) {
                                    $Status = "Transferir";
                                    echo "Passou Aqui 2" . "</br>";
                                }
                            }
                            break;

                        default :
                            $Status = "ND";
                            $Comprar = 0;
                        };
                    };
                    $update = Estoque::where('filial_id', $Filiais->id)
                                ->where('LkProduto', $valor->LkProduto)
                                ->update(['Vendidos' => $valor->Vendidos,
                                            'Minimo' => $estMinimo,
                                            'Ideal'  => $estIdeal ,
                                            'Status' => $Status,
                                            'Comprar'=> $Comprar
                                            ]);
                }
                $message = "Estoque calculado de " . $data1 . " até ". $data2 . " com Sucesso.";
            }
        }
        return redirect()->back()->with('success', $message);
    }
    public function pedidosComprarTotal()
    {
        $prodDanger         = Estoque::where('Status',"Urgente!")
                                    ->orWhere('Status',"Atenção")
                                    ->with('produto')
                                    ->groupby('LkProduto')
                                    ->orderby('Total_comprar','desc')
                                    ->get(['LkProduto', DB::raw('sum(Comprar) as Total_comprar')]);

        $prodDangerFilial   = Estoque::where('Status',"Urgente!")
                                    ->orWhere('Status',"Atenção")
                                    ->orderby('LkProduto')
                                    ->get();

        $prodDangerFilial2   = Estoque::where('Status',"Urgente!")
                                    ->where('Comprar','>','0')
                                    ->orWhere('Status',"Atenção")
                                    ->orderby('LkProduto')
                                    ->get();

        $prodDanger2         = Estoque::where('Comprar','>','0')
                                    ->with('produto')
                                    ->get();

        $countProdDanger    = Estoque::distinct()->where('Status',"Urgente!")->count(['LkProduto']);
        $sumProdDanger      = Estoque::where('Status',"Urgente!")->sum('Comprar');

        $filiaisAcomprar        = Estoque::where('Status',"Urgente!")
                                    ->orWhere('Status',"Atenção")
                                    ->with('filial')
                                    ->distinct()
                                    ->groupby('filial_id')
                                    ->get(['filial_id']);

        return view ('painel.produtos.PedidoCompra',compact('prodDanger','filiaisAcomprar','prodDangerFilial', 'prodDanger2'));
    }
    public function ProdutosEstoqueAtual()
    {
        $prod       = Estoque::distinct('LkProduto')->with('produto')->orderby('LkProduto')->get(['LkProduto']);
                                
        $prodFilial = Estoque::orderby('LkProduto')->get();

        $filiaisAcomprar = Estoque::with('filial')->distinct()->groupby('filial_id')->get(['filial_id']);

        return view ('painel.produtos.EstoqueAtual',compact('prod', 'filiaisAcomprar','prodFilial'));
    }
    public function ProdutosMaisVendidos()
    {
        $prod         = Estoque::distinct('LkProduto')->with(['produto','filial'])->orderby('LkProduto')->where('Vendidos','>','0')->get(['LkProduto']);
                                
        $prodFilial   = Estoque::orderby('LkProduto')->get();

        $filiaisAcomprar        = Estoque::with('filial')->distinct()->groupby('filial_id')->get(['filial_id']);

        return view ('painel.produtos.MaisVendidos',compact('prod', 'filiaisAcomprar','prodFilial'));
    }
    public function ProdutosTransferirCD()
    {
        $prod         = Estoque::distinct('LkProduto')->with(['produto','filial'])->orderby('LkProduto')->where('Status','Transferir')->get(['LkProduto']);
                                
        $prodFilial   = Estoque::orderby('LkProduto')->get();

        $filiaisAcomprar        = Estoque::with('filial')->distinct()->groupby('filial_id')->get(['filial_id']);

        return view ('painel.produtos.Transferir',compact('prod', 'filiaisAcomprar','prodFilial'));
    }
    public function ProdutosNaoVendidos($lkProduto,$filial,$data1,$data2)
    {
        $Vendas = MSicTabEst3A::where('filial_id',$filial)
                                ->where('Cancelada','0')
                                ->where('LkTipo','2')
                                ->wherebetween('Data',[$data1,$data2])
                                ->with(['prodVendidos','vendedor','Receb'])
                                ->orderBy('LkVendedor')
                                ->get();
                                
        if (count($Vendas)>0) {
            $estqAtual = 0;
            foreach($Vendas as $v){
                if($v->prodVendidos->LkProduto == $lkProduto) {
                    $estqAtual = $estqAtual + $v->prodVendidos->sum('Quantidade');
                }
            }
        }
    
        $prod         = Estoque::distinct('LkProduto')
                                ->where('Atual','>','0')
                                ->whereNull('Vendidos')
                                ->with('produto')
                                ->orderby('LkProduto')
                                ->get(['LkProduto']);
                                
        $prodFilial   = Estoque::orderby('LkProduto')
                                ->get();

        $filiaisAcomprar        = Estoque::with('filial')
                                ->distinct()
                                ->groupby('filial_id')
                                ->get(['filial_id']);

        return view ('painel.produtos.NaoVendidos',compact('prod', 'filiaisAcomprar','prodFilial'));
    }

    public function destroy($id)
    {
        $delete = Estoque::destroy($id);

        if ($delete) {
            return redirect()->route('PedComprarTotal')->with('success','Quantidade atualizada com sucesso!');
        } else {
            return redirect()->route('estoques.destroy', $id)->with(['errors' => 'Falha ao deletar']);
        }
        
    }
    public function EstoqueAtualCD($lkProduto)
    {
        $filiaisCD = Filiais::where('ativo','1')->where('filial_cd','1')->get();
        if (count($filiaisCD)> 0) {
            foreach($filiaisCD as $fCD) {
                $prodCDEstqAtual = Estoque::where('LkProduto', $lkProduto)->where('filial_id', $fCD)->with('produto')->get();
                if (count($prodCDEstqAtual)>0) {
                    return $prodCDEstqAtual->Atual;
                } else {
                    return 0;   
                }
            }    
        }
    }
    public function CalculaNaoVendidos($data1, $data2)
    {
        $filiaisNCD = Filiais::where('ativo','1')->where('filial_cd','0')->get();
        if (count($filiaisNCD)> 0) {
            foreach($filiaisNCD as $fNCD) {
                $Vendas = MSicTabEst3A::where('filial_id',$fNCD->id)
                                        ->where('Cancelada','0')
                                        ->where('LkTipo','2')
                                        ->wherebetween('Data',[$data1,$data2])
                                        ->with(['prodVendidos','vendedor','Receb'])
                                        ->orderBy('LkReceb')
                                        ->get();
                if (count($Vendas)>0) {
                    $valueTable = array();
                    foreach($Vendas as $v) {
                        foreach($v->prodVendidos as $prodVend) {
                            $key = array_search($prodVend->LkProduto, $valueTable);
                            if ($key > 0) {
                                $valueTable[$key][0] = $valueTable[$key][0] + $prodVend->Quantidade;
                            } else {
                                $valueTable[$prodVend->LkProduto][0] = $valueTable[$key][0] + $prodVend->Quantidade;
                            }
                        }
                    }
                }
            }
            dd($valueTable);
        }        
    }
}
