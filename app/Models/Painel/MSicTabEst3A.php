<?php

namespace App\Models\Painel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Painel\MSicTabEst3B;
use App\Models\Painel\MSicTabEst7;
use App\Models\Painel\MSicTabVend;
use App\Models\Painel\Filiais;
use App\Models\Painel\MSicTabNFCe;
class MSicTabEst3A extends Model
{
    protected $fillable = [
        'Controle',
        'filial_id',
        'Data',
        'LkTipo',
        'Nota',
        'Serie',
        'Pedido',
        'LkReceb',
        'LkVendedor',
        'LkCliente',
        'LkFornec',
        'TagCliente',
        'Comissao',
        'ComissaoVend',
        'Obs',
        'Venda',
        'LkUser',
        'CFOP',
        'DataNota',
        'Cancelada',
        'TipoDoc',
        'Frete',
        'ValorFrete',
        'LkTrans',
        'CGI',
        'RetTrib',
        'LkLoja',
        'LkCliM',
        'nfe',
        'NumCF',
        'NFE_CHAVE_TEST',
        'NFE_CHAVE_PROD',
        'NFE_CHAVE',
        'NFE_AMBIENTE',
        'ID',
        'StatusPagamento',
        'Revenda',
        'RevendaComissao'
    ];
    public function prodVendidos() {
        return $this->hasMany(MSicTabEst3B::Class, 'LkEst3A', 'Controle');
    }
    public function Receb() {
        return $this->hasOne(MSicTabEst7::Class, 'Controle', 'LkReceb');
    }
    public function vendedor() {
        return $this->hasOne(MSicTabVend::Class, 'Controle', 'LkVendedor');
    }
    public function nfce() {
        return $this->hasOne(MSicTabNFCe::Class, 'LkEst3A', 'Controle');
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
