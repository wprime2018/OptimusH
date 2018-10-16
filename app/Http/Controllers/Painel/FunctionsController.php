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
use App\Models\Painel\Comissao;
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
            $vendas = MSicTabEst3A::where('LkReceb',$Tr->Controle)
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
                    $tot_pgto += $V->prodVendidos->sum('TotVenda');
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
                        $dados[$qtde_vendas]['Valor']     = $v->prodVendidos->sum('TotVenda');
                        $tot_vendas += $v->prodVendidos->sum('TotVenda');
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
                        $semNfceCredito += $v->prodVendidos->sum('TotVenda');
                        ++$semNfceCreditoQtde;
                        break;
                    case 'D':
                        $semNfceDebito += $v->prodVendidos->sum('TotVenda');
                        ++$semNfceDebitoQtde;
                        break;
                }
                $totVendasSemNF += $v->prodVendidos->sum('TotVenda');
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
        $prod               = MSicTabEst1::where('Codigo','000323')->get();
        $filial             = Filiais::where('id',$filial_id)->get(['fantasia']);
        foreach ($filial as $f) {
            $filial_changed  = $f->fantasia;
        }
        foreach ($prod as $chip) {

        }
        $tot_vendas = 0;
        $gran_total = 0;
        $gran_qtde = 0;
        $gran_cred = 0;
        $gran_deb = 0;
        $gran_ticket = 0;
        $formas = array();
        $vendedores = MSicTabEst3A::where('filial_id',"$filial_id")
                                ->where('Cancelada','0')
                                ->wherebetween('Data',[$initial_date,$final_date])
                                ->with(['prodVendidos','vendedor','Receb'])
                                ->orderBy('LkVendedor')
                                ->distinct('LkVendedor')
                                ->get(['LkVendedor']);
        if (!empty($vendedores)) {      //Se existe vendas separa por vendedores 
            $formas['Valor'] = 0;
            $formas['Cred'] = 0;
            $formas['Deb'] = 0;
            $formas['Din'] = 0;
            $formas['Comissao'] = 0;
            $formas['CHIP']['Total'] = 0;
            $formas['CHIP']['Qtde'] = 0;
            $formas['TotalPagar'] = 0;

            foreach ($vendedores as $vendedor) {
                if ($vendedor->LkVendedor != '999') {
                    $somaVendas = MSicTabEst3A::selectRaw('sum(m_sic_tab_est3_bs.TotVenda) AS TotalVendas')
                    ->join('m_sic_tab_est3_bs', 'm_sic_tab_est3_bs.LkEst3A', '=', 'm_sic_tab_est3_as.Controle')
                    ->where('LkVendedor',$vendedor->LkVendedor)
                    ->where('m_sic_tab_est3_as.filial_id',$filial_id)
                    ->wherebetween('Data',[$initial_date,$final_date])
                    ->where('Cancelada','0')
                    ->where('LkTipo','2')
                    ->get();
                    $somaVendasChip = MSicTabEst3A::selectRaw('sum(m_sic_tab_est3_bs.TotVenda) AS TotalVendas')
                    ->selectRaw('sum(m_sic_tab_est3_bs.Quantidade) AS QtdeChip')
                    ->join('m_sic_tab_est3_bs', 'm_sic_tab_est3_bs.LkEst3A', '=', 'm_sic_tab_est3_as.Controle')
                    ->where('LkVendedor',$vendedor->LkVendedor)
                    ->where('m_sic_tab_est3_as.filial_id',$filial_id)
                    ->where('m_sic_tab_est3_bs.LkProduto',$chip->Controle)
                    ->wherebetween('Data',[$initial_date,$final_date])
                    ->where('Cancelada','0')
                    ->where('LkTipo','2')
                    ->get();
                    $somaVendasCred = MSicTabEst3A::selectRaw('sum(m_sic_tab_est3_bs.TotVenda) AS TotalVendas')
                    ->join('m_sic_tab_est3_bs', 'm_sic_tab_est3_bs.LkEst3A', '=', 'm_sic_tab_est3_as.Controle')
                    ->join('m_sic_tab_est7s', 'm_sic_tab_est7s.Controle', '=', 'm_sic_tab_est3_as.LkReceb')
                    ->where('LkVendedor',$vendedor->LkVendedor)
                    ->where('m_sic_tab_est3_as.filial_id',$filial_id)
                    ->wherebetween('Data',[$initial_date,$final_date])
                    ->where('Cancelada','0')
                    ->where('m_sic_tab_est7s.tipo','C')
                    ->where('LkTipo','2')
                    ->orderBy('Data')
                    ->get();
                    $somaVendasDeb = MSicTabEst3A::selectRaw('sum(m_sic_tab_est3_bs.TotVenda) AS TotalVendas')
                    ->join('m_sic_tab_est3_bs', 'm_sic_tab_est3_bs.LkEst3A', '=', 'm_sic_tab_est3_as.Controle')
                    ->join('m_sic_tab_est7s', 'm_sic_tab_est7s.Controle', '=', 'm_sic_tab_est3_as.LkReceb')
                    ->where('LkVendedor',$vendedor->LkVendedor)
                    ->wherebetween('Data',[$initial_date,$final_date])
                    ->where('Cancelada','0')
                    ->where('m_sic_tab_est3_as.filial_id',$filial_id)
                    ->where('m_sic_tab_est7s.tipo','D')
                    ->where('LkTipo','2')
                    ->orderBy('Data')
                    ->get();
    
                    foreach($somaVendasChip as $sV) {
                        $totVendaChip = $sV->TotalVendas;
                        $totQtdeChip = $sV->QtdeChip;
                    }
                    foreach($somaVendas as $sV) {
                        $tot_valor_vendas_vendedor = $sV->TotalVendas;
                    }
                    foreach($somaVendasCred as $sV) {
                        $tot_valor_vendas_cred = $sV->TotalVendas;
                    }
                    foreach($somaVendasDeb as $sV) {
                        $tot_valor_vendas_deb = $sV->TotalVendas;
                    }
                    $nameVendedor = MSicTabVend::where('Controle',$vendedor->LkVendedor)->get(['Nome','Comissao']);
                    foreach($nameVendedor as $nV) {
                        $nomeVendedor = $nV->Nome;
                        $comVendedor = $nV->Comissao;
                    }
                    
                    $tot_valor_vendas_din = $tot_valor_vendas_vendedor - ($tot_valor_vendas_cred + $tot_valor_vendas_deb);
                    $tot_valor_vendas_vendedor -= $totVendaChip;
                    $tot_valor_com_vendedor = $tot_valor_vendas_vendedor * ($comVendedor / 100);
                    $tot_valor_com_chip = $totVendaChip * ($perc_comissao_chip / 100);
                    
                    $formas[$nomeVendedor]['Valor'] = $tot_valor_vendas_vendedor;
                    $formas['Valor'] += $tot_valor_vendas_vendedor;
                    $formas[$nomeVendedor]['Cred'] = (int)$tot_valor_vendas_cred;
                    $formas['Cred'] += (int)$tot_valor_vendas_cred;
                    $formas[$nomeVendedor]['Deb'] = (int)$tot_valor_vendas_deb;
                    $formas['Deb'] += (int)$tot_valor_vendas_deb;
                    $formas[$nomeVendedor]['Din'] = $tot_valor_vendas_din;
                    $formas['Din'] += $tot_valor_vendas_din;
                    $formas[$nomeVendedor]['Comissao'] = $tot_valor_com_vendedor;
                    $formas['Comissao'] += $tot_valor_com_vendedor;
                    $formas[$nomeVendedor]['CHIP']['Total'] = (int)$totVendaChip;
                    $formas['CHIP']['Total'] += (int)$totVendaChip;
                    $formas[$nomeVendedor]['CHIP']['Qtde'] = (int)$totQtdeChip;
                    $formas['CHIP']['Qtde'] += (int)$totQtdeChip;
                    $formas[$nomeVendedor]['TotalPagar'] = $tot_valor_com_vendedor + $tot_valor_com_chip;
                    $formas['TotalPagar'] += $tot_valor_com_vendedor + $tot_valor_com_chip;
                }        
            }
        }
        return $formas;
    }

    public static function ranking_diario($month_date) {
    
        $Filiais        = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        $mes            = (int)substr($month_date,1,2);
        $ano            = (int)substr($month_date,3,7);
        $initial_date   = carbon::create($ano,$mes)->startOfMonth();
        $final_date     = carbon::create($ano,$mes)->endOfMonth(); 
        $periodo        = FunctionsController::mesExtenso($initial_date)['mesExtenso'] . '-' . $initial_date->year;
        $lastDayMonth   = $final_date->day;
        $formas         = Array();
    
        for ($i=1; $i <= $lastDayMonth; $i++) { 
    
            $diaSemana = FunctionsController::diaDaSemana($initial_date)['diadaSemanaAbr'];
            $dataFormat = $initial_date->format('d/m/Y') . " ". $diaSemana;
            $formas[$dataFormat]['data1'] = $initial_date->startOfDay()->toDateTimeString();
            $formas[$dataFormat]['data2'] = $initial_date->endOfDay()->toDateTimeString();
            $total_vendas_filial = 0;
            $total_vendas_filialCred = 0;
            $total_vendas_filialDeb = 0;
            $total_vendas_filialDin = 0;
            foreach($Filiais as $f) {
                $vendas = MSicTabEst3A::where('filial_id',$f->id)
                                        ->where('Cancelada','0')
                                        ->where('LkTipo','2')
                                        ->wherebetween('Data',[$formas[$dataFormat]['data1'],$formas[$dataFormat]['data2']])
                                        ->with(['prodVendidos'])
                                        ->orderBy('Data')
                                        ->get();
                
                $formas[$dataFormat][$f->codigo]['Qtde'] = count($vendas);
                $tot_valor_vendas_dia = 0;
                $tot_valor_vendas_diaCred = 0;
                $tot_valor_vendas_diaDeb = 0;
                $tot_valor_vendas_diaDin = 0;
                
                if(count($vendas)) {
    
                    foreach ($vendas as $v) {
    
                        if ($v->Receb()->count() > 0) {
    
                            switch ($v->Receb->tipo) {
                                case 'C':
                                    $tot_valor_vendas_diaCred += $v->prodVendidos->sum('TotVenda');
                                    break;
                                case 'D':
                                    $tot_valor_vendas_diaDeb += $v->prodVendidos->sum('TotVenda');
                                    break;
                                default:
                                    $tot_valor_vendas_diaDin += $v->prodVendidos->sum('TotVenda');
                            }
    
                        }
    
                    }
    
                    $formas[$dataFormat][$f->codigo]['Total'] = $tot_valor_vendas_diaCred + $tot_valor_vendas_diaDeb + $tot_valor_vendas_diaDin;
                    $formas[$dataFormat][$f->codigo]['Cred']  = $tot_valor_vendas_diaCred;
                    $formas[$dataFormat][$f->codigo]['Deb']   = $tot_valor_vendas_diaDeb;
                    $formas[$dataFormat][$f->codigo]['Din']   = $tot_valor_vendas_diaDin;
                    $total_vendas_filial     += $formas[$dataFormat][$f->codigo]['Total'];
                    $total_vendas_filialCred += $tot_valor_vendas_diaCred;
                    $total_vendas_filialDeb  += $tot_valor_vendas_diaDeb;
                    $total_vendas_filialDin  += $tot_valor_vendas_diaDin;
            
                } else {
    
                    $formas[$dataFormat][$f->codigo]['Total'] = 0;
                    $formas[$dataFormat][$f->codigo]['Cred'] = 0;
                    $formas[$dataFormat][$f->codigo]['Deb'] = 0;
                    $formas[$dataFormat][$f->codigo]['Din'] = 0;
                }
            }
            $initial_date = $initial_date->addDay();
        }
        
        $formas['periodo'] = $periodo;
        return $formas;
    }
    
    public static function vendasMinMaxData() {
        $dataMin = MSicTabEst3A::where('Cancelada','0')
                                ->where('LkTipo','2')
                                ->orderBy('Data')
                                ->min('Data');
        $dataMax = MSicTabEst3A::where('Cancelada','0')
                                ->where('LkTipo','2')
                                ->orderBy('Data')
                                ->max('Data');
        $min = Carbon::createFromformat('Y-m-d H:s:i',$dataMin);
        $max = Carbon::createFromformat('Y-m-d H:s:i',$dataMax);
        $diffMonth = $min->diffInMonths($max);
        $periodo = Array();
        $periodo['diff'] = $diffMonth;
        for ($i=0; $i <= ($diffMonth+1); $i++) { 
            $mes = $max->month;
            $ano = $max->year;
            $extenso = $ano . ' - ' . FunctionsController::mesExtenso($max)['mesExtenso'];
            $periodo[$i]['extenso'] =  $extenso;
            $periodo[$i]['mes'] = $mes;
            $periodo[$i]['ano'] = $ano;
            $max = $max->SubMonth();
        }               
        return compact('dataMin', 'dataMax','periodo');
    }

    public static function mesExtenso($datainCarbon) {
        switch ($datainCarbon->month) {
            case $datainCarbon->month == 1:
                $mesExtenso = 'Janeiro';
                $mesExtensoAbr = 'Jan';
                break;
            case $datainCarbon->month == 2:
                $mesExtenso = 'Fevereiro';
                $mesExtensoAbr = 'Fev';
                break;
            case $datainCarbon->month == 3:
                $mesExtenso = 'Março';
                $mesExtensoAbr = 'Mar';
                break;
            case $datainCarbon->month == 4:
                $mesExtenso = 'Abril';
                $mesExtensoAbr = 'Abr';
                break;
            case $datainCarbon->month == 5:
                $mesExtenso = 'Maio';
                $mesExtensoAbr = 'Mai';
                break;
            case $datainCarbon->month == 6:
                $mesExtenso = 'Junho';
                $mesExtensoAbr = 'Jun';
                break;
            case $datainCarbon->month == 7:
                $mesExtenso = 'Julho';
                $mesExtensoAbr = 'Jul';
                break;
            case $datainCarbon->month == 8:
                $mesExtenso = 'Agosto';
                $mesExtensoAbr = 'Ago';
                break;
            case $datainCarbon->month == 9:
                $mesExtenso = 'Setembro';
                $mesExtensoAbr = 'Set';
                break;
            case $datainCarbon->month == 10:
                $mesExtenso = 'Outubro';
                $mesExtensoAbr = 'Out';
                break;
            case $datainCarbon->month == 11:
                $mesExtenso = 'Novembro';
                $mesExtensoAbr = 'Nov';
                break;
            case $datainCarbon->month == 12:
                $mesExtenso = 'Dezembro';
                $mesExtensoAbr = 'Dez';
                break;

            default:
                # code...
                break;
        }
        return compact('mesExtenso', 'mesExtensoAbr');
    }

    public static function diaDaSemana($dateinCarbon) {
        $diadaSemana = $dateinCarbon->format('D');
        switch ($diadaSemana) {
            case 'Sun':
                $diadaSemana = 'Domingo';
                $diadaSemanaAbr = 'Dom';
                break;
            case 'Mon':
                $diadaSemana = 'Segunda-feira';
                $diadaSemanaAbr = 'Seg';
                break;
            case 'Tue':
                $diadaSemana = 'Terça-feira';
                $diadaSemanaAbr = 'Ter';
                break;
            case 'Wed':
                $diadaSemana = 'Quarta-feira';
                $diadaSemanaAbr = 'Qua';
                break;
            case 'Thu':
                $diadaSemana = 'Quinta-feira';
                $diadaSemanaAbr = 'Qui';
                break;
            case 'Fri':
                $diadaSemana = 'Sexta-feira';
                $diadaSemanaAbr = 'Sex';
                break;
            case 'Sat':
                $diadaSemana = 'Sábado';
                $diadaSemanaAbr = 'Sáb';
                break;
        }
        return compact('diadaSemana', 'diadaSemanaAbr');
    }

    public static function vendas_pgto($initial_date, $final_date) {
   
        $formas = array();
        $receb = MSicTabEst3A::where('Cancelada','0')
                            ->where('LkTipo','2')
                            ->orWhere('LkTipo','6')
                            ->wherebetween('Data',[$initial_date,$final_date])
                            ->with(['prodVendidos','Receb','filial'])
                            ->groupby('LkReceb')
                            ->distinct()
                            ->get(['LkReceb']);
        $filial = MSicTabEst3A::where('Cancelada','0')
                            ->where('LkTipo','2')
                            ->orWhere('LkTipo','6')
                            ->wherebetween('Data',[$initial_date,$final_date])
                            ->with(['prodVendidos','Receb','filial'])
                            ->groupby('filial_id')
                            ->distinct()
                            ->get(['filial_id']);

        $granTotalVendas = 0;
        $granTotalDin = 0;
        $granTotalCred = 0;
        $granTotalDeb = 0;
        $granTotalQtde = 0;
        $granTotalNFCe = 0;
        foreach ($receb as $rec) {
            foreach ($filial as $f) {
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Total'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Qtde_Vendas'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Cred']['Total'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Cred']['Qtde'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Deb']['Total'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Deb']['Qtde'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Din']['Total'] = 0; 
                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Din']['Qtde'] = 0;
                $vendas = MSicTabEst3A::where('filial_id',$f->filial->id)
                                    ->where('LkReceb',$rec->Receb->Controle)
                                    ->where('Cancelada','0')
                                    ->where('LkTipo','2')
                                    ->wherebetween('Data',[$initial_date,$final_date])
                                    ->with(['prodVendidos','Receb','filial'])
                                    ->orderBy('Data')
                                    ->get();
                if(count($vendas)>0){

                    $valorVenda = 0;
                    $valorVendaCred = 0;
                    $valorVendaDeb = 0;
                    $valorVendaDin = 0;
                    $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Qtde_Vendas'] = count($vendas);
                    $granTotalQtde += count($vendas);

                    foreach($vendas as $V){
                        $valorVenda += $V->prodVendidos->sum('TotVenda');
                        $granTotalVendas += $valorVenda;
                        $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Total'] = $valorVenda;
                        
                        if (!is_null($V->Nota))
                            $granTotalNFCe += $valorVenda;

                        switch ($V->Receb->tipo) {
                            case 'C':
                                $valorVendaCred += $valorVenda;
                                $granTotalCred += $valorVenda;
                                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Cred']['Total'] = $valorVendaCred;
                                ++$recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Cred']['Qtde'];
                                break;
                            case 'D':
                                $valorVendaDeb += $valorVenda;
                                $granTotalDeb += $valorVenda;
                                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Deb']['Total'] = $valorVendaDeb;
                                ++$recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Deb']['Qtde'];
                                break;
                            default:
                                $valorVendaDin += $valorVenda;
                                $granTotalDin += $valorVenda;
                                $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Din']['Total'] = $valorVendaDin;
                                ++$recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Din']['Qtde'];
                        }
                        $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['TicketM'] = $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Total'] / $recebim[$rec->Receb->Recebimento][$f->filial->codigo]['Qtde_Vendas'];
                    }
                }
                $gt['GranTotalVendas'] = $granTotalVendas;
                $gt['GranTotalCred'] = $granTotalCred;
                $gt['GranTotalDeb'] = $granTotalDeb;
                $gt['GranTotalDin'] = $granTotalDin;
                $gt['GranTotalQtde'] = $granTotalQtde;
                $gt['GranTotalNFCe'] = $granTotalNFCe;
            }
        }
       ksort($recebim);
        foreach ($recebim as $rec => $filiais) {
            ksort($filiais);
        }
        dd(compact(['recebim','filiais','gt']));
        //return compact(['recebim','filiais']);
    }
}