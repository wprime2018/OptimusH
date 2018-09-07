<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Painel\MSicTabEst3A;
use App\Models\Painel\MSicTabEst3B;
use App\Models\Painel\MSicTabEst1;
use App\Models\Painel\MSicTabEst7;
use App\Models\Painel\Filiais;
use App\Models\Painel\MSicTabNFCe;
use App\Models\Painel\MSicTabVend;
use Carbon\Carbon;

class FunctionsController extends Controller
{
    public static function ranking_vendas($filial_id, $initial_date, $final_date)
    {
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        $formas = array();
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_din = 0;
        $gran_ticket = 0;
        $tot_filial_din = 0;
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
                                    ->where('filial_id',$filial_id)
                                    ->where('Cancelada','0')
                                    ->where('LkTipo','2')
                                    ->wherebetween('Data',[$initial_date,$final_date])
                                    ->with('prodVendidos')
                                    ->get();
            $tot_qtde_receb = $Vendas->count();
            if(count($Vendas)>0){
                $tot_pgto = 0;
                foreach($Vendas as $V){
                    $tot_pgto += $V->prodVendidos->sum('Total');
                }
                $formas[$Tr->tipo][$Tr->Recebimento] = Array ('Qtde' => $tot_qtde_receb, 'Total' => $tot_pgto) ;
                $tot_filial_qtde += $tot_qtde_receb;
                $tot_filial_valor += $tot_pgto; 

                switch ($V->Receb->tipo) {
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

                $ticket_medio = $tot_filial_valor / $tot_filial_qtde;
    
                $formas[$Tr->tipo][$Tr->Recebimento]['Qtde_Vendas']    = $tot_filial_qtde;
                $formas[$Tr->tipo][$Tr->Recebimento]['TicketM']        = $ticket_medio;
                $formas[$Tr->tipo][$Tr->Recebimento]['Cred']           = $tot_filial_cred;
                $formas[$Tr->tipo][$Tr->Recebimento]['Deb']            = $tot_filial_deb;
                $formas[$Tr->tipo][$Tr->Recebimento]['Din']            = $tot_filial_din;
                $formas[$Tr->tipo][$Tr->Recebimento]['TotalVendas']    = $tot_filial_valor;
            }else{
                $formas[$Tr->tipo][$Tr->Recebimento] = Array ('Qtde' => $tot_qtde_receb, 'Total' => 0) ;
            }
        }
        $formas['GranTotal']     = $tot_filial_valor;
        $formas['GranQtde']      = $tot_filial_qtde;
        $formas['GranCred']      = $tot_filial_cred;
        $formas['GranCredQtde']  = $tot_filial_qtde_cred;
        $formas['GranTotalDeb']  = $tot_filial_deb;
        $formas['GranDebQtde']   = $tot_filial_qtde_deb;

        return $formas;
    }

    public static function calcula_nfce($filial_id, $initial_date, $final_date) {

        $nfces = MSicTabNFCe::where('filial_id',"$filial_id")
                            ->wherebetween('DataHora',[$initial_date,$final_date])
                            ->orderBy('DataHora')
                            ->get();
        
        if (count($nfces) > 0) {
            $tot_vendas = 0;
            $qtde_vendas = 0;
            $dados = Array();
            $seq_nfce = MSicTabNFCe::where('filial_id',"$filial_id")
                        ->wherebetween('DataHora',[$initial_date,$final_date])
                        ->orderBy('DataHora')
                        ->min('Numero');
            $seq_nfce_max = MSicTabNFCe::where('filial_id',"$filial_id")
                        ->wherebetween('DataHora',[$initial_date,$final_date])
                        ->orderBy('DataHora')
                        ->max('Numero');
            $teste_seq_nfce = '';
            foreach($nfces as $nfce) {
                $venda = MSicTabEst3A::where('filial_id',"$filial_id")
                                        ->where('Controle',"$nfce->LkEst3A")
                                        ->with(['prodVendidos','Receb'])
                                        ->get();
                
                if ($nfce->Numero != $seq_nfce) {
                    $teste_seq_nfce = $teste_seq_nfce .', '. $seq_nfce;
                    $seq_nfce = $nfce->Numero;
                } 
                
                if (count($venda) > 0 ) {
                    foreach ($venda as $v) {
                        $dados[$qtde_vendas]['Numero']    = $nfce->Numero;
                        $dados[$qtde_vendas]['Data']      = $v->Data;
                        $dados[$qtde_vendas]['Chave']     = $nfce->Chave;
                        $dados[$qtde_vendas]['Recibo']    = $nfce->Recibo == '' ? 'Enviado!' : $nfce->Recibo ;
                        $dados[$qtde_vendas]['Receb']     = $v->Receb->Recebimento;
                        $dados[$qtde_vendas]['Valor']     = $v->prodVendidos->sum('Total');
                        $tot_vendas += $v->prodVendidos->sum('Total');
                    }
                } else {
                    $dados[$qtde_vendas]['Numero']    = $nfce->Numero;
                    $dados[$qtde_vendas]['Data']      = $nfce->DataHora;
                    $dados[$qtde_vendas]['Chave']     = $nfce->Chave;
                    $dados[$qtde_vendas]['Recibo']    = $nfce->Numero == '' ? 'Enviado!' : $nfce->Numero ;
                    $dados[$qtde_vendas]['Receb']     = '*** Cancelada ***';
                    $dados[$qtde_vendas]['Valor']     = 0;
                }
                ++$qtde_vendas;
                ++$seq_nfce;
            }
        } 

        $filial_changed = Filiais::where('id',"$filial_id")->get(['fantasia']);
        foreach ($filial_changed as $fc) {
            $filial_changed = $fc->fantasia;
        }
        $vendasSemNFCeCC = MSicTabEst3A::where('filial_id',"$filial_id")
                                ->where('Cancelada','0')
                                ->where('LkTipo','2')
                                ->whereNull('Nota')
                                ->wherebetween('Data',[$initial_date,$final_date])
                                ->with(['prodVendidos','vendedor','Receb'])
                                ->orderBy('Data')
                                ->get();

        $semNfceCredito = 0;
        $semNfceCreditoQtde = 0;
        $semNfceDebito = 0;
        $semNfceDebitoQtde = 0;
        $totVendasSemNF = 0;
        $qtdeVendasSemNF = 0;
        if (count($vendasSemNFCeCC) > 0) {  ///executa o processo se houver vendas sem nota.

            foreach ($vendasSemNFCeCC as $v) {
                switch ($v->Receb->tipo) {
                    case 'C':
                        $semNfceCredito += $v->prodVendidos->sum('Total');
                        ++$semNfceCreditoQtde;
                        break;
                    case 'D':
                        $semNfceDebito += $v->prodVendidos->sum('Total');
                        ++$semNfceDebitoQtde;
                        break;
                }
                $totVendasSemNF += $v->prodVendidos->sum('Total');
                ++$qtdeVendasSemNF;
            }
        }
        $dados['SemNFCartao']['Valor'] = $semNfceCredito + $semNfceDebito;
        $dados['SemNFCartao']['Qtde']  = $semNfceCreditoQtde + $semNfceDebitoQtde;
        $dados['Periodo'] = Carbon::createFromFormat('Y-m-d',$initial_date)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d',$final_date)->format('d/m/Y');
        $dados['TotalSemNF'] = $totVendasSemNF;
        $dados['QtdeSemNF'] = $qtdeVendasSemNF;
        $dados['TotalComNF'] = $tot_vendas;
        $dados['QtdeComNF'] = $qtde_vendas;
        $dados['filial_changed'] = $filial_changed;
        $dados['NoFind'] = $teste_seq_nfce;
        return $dados;
    }

    public static function calcula_comissao($filial_id, $initial_date, $final_date, $perc_comissao_chip) {
        $TipoRecebimentos   = MSicTabEst7::get(['id','Controle','Recebimento','tipo']);
        $listVend           = MSicTabVend::get(['id','Controle','Nome','Comissao', 'DataInc']);
        $prod               = MSicTabEst1::where('Codigo','000323')->get();
        foreach ($prod as $chip) {

        }
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_ticket = 0;
        $formas = array();
        $Vendas = MSicTabEst3A::where('filial_id',"$filial_id")
                                ->where('Cancelada','0')
                                ->where('LkTipo','2')
                                ->wherebetween('Data',[$initial_date,$final_date])
                                ->with(['prodVendidos','vendedor','Receb'])
                                ->orderBy('LkVendedor')
                                ->get();
        $vendedores = MSicTabEst3A::where('filial_id',"$filial_id")
                                ->where('Cancelada','0')
                                ->where('LkTipo','2')
                                ->wherebetween('Data',[$initial_date,$final_date])
                                ->with(['prodVendidos','vendedor','Receb'])
                                ->orderBy('LkVendedor')
                                ->distinct('LkVendedor');
        dd($vendedores);
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
            // Calculando comissoes conforme tabela de metas. 
            $aComFilial = Comissao::where('filial_id',$f->id)
                                    ->where('tipo','1')
                                    ->orderby('Vendas')
                                    ->get();
            $aComVend = Comissao::where('filial_id',$f->id)
                                    ->where('tipo','2')
                                    ->orderby('Vendas')
                                    ->get();
            
            $totalVendasFilial = FunctionsController::ranking_vendas($f->id,$data1,$data2);
            
            $totalVendasFilial->GranTotal;

            dd($totalVendasFilial);

            if (count($aComVend)>0) {
                $i = 0;
                foreach($aComVend as $key => $valor) {    // Calculando a comissão pela venda do vendedor
                    if ($tot_valor_vendas_vendedor > $valor->vendas && $valor->comissao > $v->vendedor->Comissao) {
                        if (isset($formas[$f->fantasia][$lV->Nome]['CHIP'])) {
                            $tot_valor_com_vendedor = (($tot_valor_vendas_vendedor - $formas[$f->fantasia][$lV->Nome]['CHIP']['Total'] ) * ($valor->comissao / 100));
                        } else {
                            $tot_valor_com_vendedor = ($tot_valor_vendas_vendedor * ($valor->comissao / 100));
                        } 
                        $comissao_paga = $valor->comissao;
                        ++$i;
                        $bateuMeta = $i;
                    } else {
                        if (isset($formas[$f->fantasia][$lV->Nome]['CHIP'])) {
                            $tot_valor_com_vendedor = (($tot_valor_vendas_vendedor - $formas[$f->fantasia][$lV->Nome]['CHIP']['Total'] ) * ($v->vendedor->Comissao / 100));
                        } else {
                            $tot_valor_com_vendedor = ($tot_valor_vendas_vendedor * ($v->vendedor->Comissao / 100));
                        } 
                    }
                }
            } 
            // Calculando comissoes de CHIPS
            if (isset($formas[$f->fantasia][$lV->Nome]['CHIP'])) {
                $formas[$f->fantasia][$lV->Nome]['TotalPagar'] = $tot_valor_com_vendedor + $formas[$f->fantasia][$lV->Nome]['CHIP']['TotalPagar'];
            } else {
                $formas[$f->fantasia][$lV->Nome]['TotalPagar'] = $tot_valor_com_vendedor;
            } 
            $tot_valor_vendas_din += $v->prodVendidos->sum('Total');
            $tot_valor_com_vendedor = number_format(($tot_valor_com_vendedor),2,',','.');
            $formas[$f->fantasia][$lV->Nome]['Valor'] = number_format($tot_valor_vendas_vendedor,2,',','.');
            $formas[$f->fantasia][$lV->Nome]['Qtde'] = $tot_qtde_vendas_vendedor;
            $formas[$f->fantasia][$lV->Nome]['TicketM'] = number_format(($tot_valor_vendas_vendedor / $tot_qtde_vendas_vendedor),2,',','.');
            $formas[$f->fantasia][$lV->Nome]['Cred'] = number_format($tot_valor_vendas_cred,2,',','.');
            $formas[$f->fantasia][$lV->Nome]['Deb'] = number_format($tot_valor_vendas_deb,2,',','.');
            $formas[$f->fantasia][$lV->Nome]['Din'] = number_format($tot_valor_vendas_din,2,',','.');
            $formas[$f->fantasia][$lV->Nome]['Comissao'] = $tot_valor_com_vendedor;
            if (isset($comissao_paga))
                $formas[$f->fantasia][$lV->Nome]['Comissao_Paga'] = $comissao_paga;
            else 
                $formas[$f->fantasia][$lV->Nome]['Comissao_Paga'] = $v->vendedor->Comissao;
            
            if (isset($bateuMeta) && ($bateuMeta > 0))
                $formas[$f->fantasia][$lV->Nome]['BateuMeta'] = true;
            else 
                $formas[$f->fantasia][$lV->Nome]['BateuMeta'] = false;
        }
    }
}
