<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;
use Carbon\Carbon;
use App\Models\Painel\MSicTabEst1;      //Produtos
use App\Models\Painel\MSicTabEst7;      //Tipos de Recebimentos
use App\Models\Painel\MSicTabVend;      //Vendedores
use App\Models\Painel\MSicTabEst3A;     //Vendas
use App\Models\Painel\MSicTabEst3B;     //Produtos Vendidos
use App\Models\Painel\MSicTabNFCe;
use App\Models\Painel\Estoque;          //Produtos Vendidos com estoque calculado
use App\Models\Painel\Filiais;
use App\Models\Painel\Setores;
use App\Models\Painel\ImportFileSic;


class SicTabEst1Controller extends Controller
{
    /*private $Produtos;
    
    public function __construct(MSicTabEst1 $Produtos)
    {
        $this->MSicTabEst1 = $Produtos;
    }*/
    
    public function index()
    {
        $Produtos = MSicTabEst1::where('Inativo','=', '0')
                                ->with('prodEstoque')
                                ->limit(100)
                                ->get();
        $ListFiliais = Filiais::where('ativo','=', '1')->get();
        $totCountFiliais = Filiais::where('ativo','=', '1')->count();
        return view('painel.produtos.Produtos', compact('ListFiliais','Produtos','totCountFiliais'));
    }
    public function indexVendas()
    {
        /*$Vendas = MSicTabEst3A::join('m_sic_tab_est7s', 'm_sic_tab_est3_as.LkReceb', '=', 'm_sic_tab_est7s.Controle')
                                ->join('tb_filiais', 'm_sic_tab_est3_as.filial_id', '=', 'tb_filiais.id')
                                ->join('m_sic_tab_vends', 'm_sic_tab_est3_as.LkVendedor', '=', 'm_sic_tab_vends.Controle')
                                ->select('m_sic_tab_est3_as.*', 'tb_filiais.fantasia','m_sic_tab_vends.Nome','m_sic_tab_est7s.Recebimento')
                                ->with('prodVendidos')
                                ->limit(100)
                                ->get();*/
        $Vendas = MSicTabEst3A::join('m_sic_tab_est7s', 'm_sic_tab_est3_as.LkReceb', '=', 'm_sic_tab_est7s.Controle')
                                ->join('tb_filiais', 'm_sic_tab_est3_as.filial_id', '=', 'tb_filiais.id')
                                ->join('m_sic_tab_vends', 'm_sic_tab_est3_as.LkVendedor', '=', 'm_sic_tab_vends.Controle')
                                ->select('m_sic_tab_est3_as.*', 'tb_filiais.fantasia','m_sic_tab_vends.Nome','m_sic_tab_est7s.Recebimento')
                                ->with('prodVendidos')
                                ->limit(100)
                                ->get();
        
        $ListFiliais = Filiais::get();
        return view('painel.vendas.Vendas', compact('ListFiliais','Vendas'));
    }
        
    public function importTabVend(Request $request) //Vendedores
    {
        
        if($request->file('imported-file'))
        {
            $path = $request->file('imported-file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            $dataImport[] = [
                'path_file' => $path
            ];
    
            if(!empty($data) && $data->count())
            {
                DB::statement("SET foreign_key_checks=0");
                MSicTabVend::truncate();
                DB::statement("SET foreign_key_checks=1");
                
                foreach ($data->toArray() as $row)
                {
                    if(!empty($row))
                    {
                        // Começando os testes dos dados para ajustar ao DB Atual
                        if(empty($row['datainc'])) { $row['datainc'] = "2000-01-01 00:00:00"; } else{
                            $dataHoraCSV = $row['datainc'] . " 00:00:00";
                            $dt = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV);
                            $row['datainc'] = $dt->toDateTimeString();
                        }
                        
                        if(empty($row['tipodoc'])) { $row['tipodoc'] = ""; }
                        $dataAtual = Carbon::now();

                        $dataArray[] =
                        [
                            'Controle' => $row['controle'],
                            'Nome' => $row['nome'],
                            'Comissao' => $row['comissao'],
                            'DataInc' => $row['datainc'],
                            'Tipo' => $row['tipo'],
                            'Codigo' => $row['codigo'],
                            'created_at' => $dataAtual
                        ];
                    }
                }
                if(!empty($dataArray))
                {
                    MSicTabVend::insert($dataArray);
                    $dataArrayni[] =
                        [
                            'Controle' => 999,
                            'Nome' => 'Vendedor nao identificado',
                            'Comissao' => 0,
                            'DataInc' => $dataAtual,
                            'Tipo' => 'Interno',
                            'Codigo' => Null,
                            'created_at' => $dataAtual
                        ];
                    MSicTabVend::insert($dataArrayni);

                }
                if(!empty($dataImport))
                {
                    ImportFileSic::create($dataImport);
                }    
                return back();
            }
        }
    }
    public function importTabEst7(Request $request) //Recebimentos
    {
        
        if($request->file('imported-file'))
        {
            $path = $request->file('imported-file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            $dataImport[] = [
                'path_file' => $path
            ];
    
            if(!empty($data) && $data->count())
            {
                DB::statement("SET foreign_key_checks=0");
                MSicTabEst7::truncate();
                DB::statement("SET foreign_key_checks=1");
                
                foreach ($data->toArray() as $row)
                {
                    if(!empty($row))
                    {
                        // Começando os testes dos dados para ajustar ao DB Atual
                        
                        if($row['fixo'] == 'True') { $row['fixo'] = 1; } else { $row['fixo'] = 0;}
                        $dataAtual = Carbon::now();

                        $dataArray[] =
                        [
                            'Controle' => $row['controle'],
                            'Recebimento' => $row['recebimento'],
                            'Fixo' => $row['fixo'],
                            'frEcf' => $row['frecf'],
                            'frID' => $row['frid'],
                            'created_at' => $dataAtual
                        ];
                    }
                }
                if(!empty($dataArray))
                {
                    MSicTabEst7::insert($dataArray);
                }
                if(!empty($dataImport))
                {
                    ImportFileSic::create($dataImport);
                }    
                return back();
            }
        }
    }
    public function importVendas(Request $request)
    {
        if($request->file('imported-file1'))        // Importando table Produtos com estoque Atual. 
        {
            echo "Atualizando Produtos";
            $path = $request->file('imported-file1')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            $dataImport[] = [
                'filial_id' => $request['filial_id'],
                'path_file' => $path
            ];

            if(!empty($data) && $data->count())
            {
                DB::statement("SET foreign_key_checks=0");
                MSicTabEst1::truncate();
                Estoque::where('filial_id','=',$request['filial_id'])->delete();
                DB::statement("SET foreign_key_checks=1");
                foreach ($data->toArray() as $row)
                {
                    if(!empty($row)) {
                        //dd($row);
                        if (empty($row['ultreaj'])) { 
                            $row['ultreaj'] = "2000-01-01 00:00:00"; 
                        } else {
                            $dataHoraCSV    = $row['ultreaj'] . " 00:00:00";
                            $dt  = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV);
                            $row['ultreaj']     = $dt->toDateTimeString(); 
                        }

                        if (empty($row['qntembalagem'])) { 
                            $row['qntembalagem'] = "2000-01-01 00:00:00"; 
                        } else {
                            $dataHoraCSV2   = $row['qntembalagem'] . " 00:00:00";
                            $dt2 = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV2);
                            $row['qntembalagem']    = $dt2->toDateTimeString(); 
                        }

                        if (empty($row['previsao'])) { 
                            $row['previsao'] = "2000-01-01 00:00:00"; 
                        } else {
                            $dataHoraCSV2   = $row['previsao'];
                            $dt2 = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV2);
                            $row['previsao']    = $dt2->toDateTimeString(); 
                        }

                        if (empty($row['ippt'])) { 
                            $row['ippt'] = "2000-01-01 00:00:00"; 
                        } else {
                            $dataHoraCSV3   = $row['ippt'] . " 00:00:00";
                            $dt3 = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV3);
                            $row['ippt']  = $dt3->toDateTimeString(); 
                        }
                        if($row['obs'] == 'True'){ $row['obs'] = 1;} else { $row['obs'] = 0;}
                        if($row['foto'] == 'True'){ $row['foto'] = 1;} else { $row['foto'] = 0;}
                        if($row['armazenamento'] == 'True'){ $row['armazenamento'] = 1;} else { $row['armazenamento'] = 0;}
                        // Começando os testes dos dados para ajustar ao DB Atual
                        $dataAtual = Carbon::now();
                        
                        $dataEstoque[] = [
                            'filial_id' => $request['filial_id'],
                            'LkProduto' => $row['controle'],
                            'Atual' => $row['quantidade'],
                            'Minimo' => 0,
                            'Ideal' => 0,
                            'created_at' => $dataAtual
                        ];
                    
                    
                        $dataArray[] =
                        [
                            'Controle' => $row['controle'],
                            //'filial_id' => $request['filial_id'],
                            'Codigo' => $row['codigo'],
                            'CodInterno' => $row['codinterno'],
                            'Produto' => $row['produto'],
                            'LkSetor' => $row['lksetor'],
                            'Fabricante' => $row['fabricante'],
                            'LkFornec' => $row['lkfornec'],
                            'PrecoCusto' => $row['precocusto'],
                            'CustoMedio' => $row['customedio'],
                            'PrecoVenda' => $row['precovenda'],
                            'Quantidade' => $row['quantidade'],
                            'EstMinimo' => $row['estminimo'],
                            'Unidade' => $row['unidade'],
                            'Lucro' => $row['lucro'],
                            'Comissao' => $row['comissao'],
                            'Moeda' => $row['moeda'],
                            'UltReaj' => $row['ultreaj'],
                            'NaoSaiTabela' => $row['foto'],
                            'Inativo' => $row['obs'],
                            'CodIPI' => $row['naosaitabela'],
                            'IPI' => $row['inativo'],
                            'CST' => $row['codipi'],
                            'ICMS' => $row['ipi'],
                            'BaseCalculo' => $row['cst'],
                            'PesoBruto' => $row['icms'],
                            'PesoLiq' => $row['basecalculo'],
                            'LkModulo' => $row['pesobruto'],
                            'Armazenamento' => $row['pesoliq'],
                            'QntEmbalagem' => $row['lkmodulo'],
                            'ELV' => $row['armazenamento'],
                            'Previsao' => $row['qntembalagem'],
                            'DataFoto' => $row['elv'],
                            'DataInc' => $row['previsao'],
                            'LkUserInc' => $row['datafoto'],
                            'CodEx' => $row['datainc'],
                            'IVA_ST' => $row['lkuserinc'],
                            'PFC' => $row['codex'],
                            'IPI_CST' => $row['iva_st'],
                            'IPI_BaseCalc' => $row['pfc'],
                            'IPPT' => $row['ipi_cst'],
                            'IAT' => $row['ipi_basecalc'],
                            'DataUltMov' => $row['ippt'],
                            'EAD' => $row['iat'],
                            'cEAN' => $row['dataultmov'],
                            'cEANTrib' => $row['ead'],
                            'cProdANP' => $row['cean'],
                            'CEST' => $row['ceantrib'],
                            'Origem' => $row['cprodanp'],
                            'created_at' => $dataAtual
                        ];
                    }
                }
                if(!empty($dataArray))
                {
                    //dd($dataArray);
                    foreach (array_chunk($dataArray,500) as $t) {
                        MSicTabEst1::insert($t);
                    }
                    if(!empty($dataEstoque))
                    {
                        foreach (array_chunk($dataEstoque,500) as $e) {
                            Estoque::insert($e);
                        }    
                    }
                    if(!empty($dataImport))
                    {
                        ImportFileSic::create($dataImport);
                    }    
                    $message = "Produtos importados"; 
                }
            }
        } else {$message = "Você não informou o arquivo de produtos";  }
        if($request->file('imported-file2'))        // Importando table Vendas com dados Completos
        {
            $path2 = $request->file('imported-file2')->getRealPath();
            $data2 = Excel::load($path2, function($reader) {})->get();

            $dataImport2[] = [
                'filial_id' => $request['filial_id'],
                'path_file' => $request->file('imported-file2')->path()
            ];

            if(!empty($data2) && $data2->count() > 0)
            {
                DB::statement("SET foreign_key_checks=0");
                MSicTabEst3A::where('filial_id','=',$request['filial_id'])->delete();
                DB::statement("SET foreign_key_checks=1");
                
                foreach ($data2->toArray() as $row2)
                {
                    if(!empty($row2))
                    {
                        // Começando os testes dos dados para ajustar ao DB Atual
                        if(empty($row2['data'])) { 
                            $row2['data'] = "2000-01-01 00:00:00"; 
                        } else {
                            if(empty($row2['hora'])) { $row2['hora'] = "00:00:00"; }
                            $dataHoraCSV = $row2['data'] . " " . $row2['hora'];
                            $dt = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV);
                            $row2['data'] = $dt->toDateTimeString(); 
                        }
                        
                        if(empty($row2['datanota'])) { 
                            $row2['datanota'] = 0; 
                        } else {
                            if($row2['datanota'] == 'True'){ $row2['datanota'] = 1;} else { $row2['datanota'] = 0;}
                        }
                        if(empty($row2['cfop'])) { 
                            $row2['cfop'] = "2000-01-01 00:00:00"; 
                        } else {
                            $datanota = $row2['cfop'] . " 00:00:00";
                            $dt2 = Carbon::createFromFormat('d/m/Y H:i:s', $datanota);
                            $row2['cfop'] = $dt2->toDateTimeString(); 
                        }

                        if(empty($row2['tipodoc'])) { $row2['tipodoc'] = ""; }
                        if(empty($row2['lkvendedor'])) {$row2['lkvendedor'] = 999; }
                        if($row2['lkvendedor'] == '') {$row2['lkvendedor'] = 999; }
                        if($row2['lkvendedor'] == 0) {$row2['lkvendedor'] = 999; }

                        //Testando se o vendedor está cadastrado. 
                        $testVendedor = MSicTabVend::where('Controle',$row2['lkvendedor'])->get();
                        if (count($testVendedor) == 0) {$row2['lkvendedor'] = 999; }
                        
                        if($row2['lkreceb'] == 0) {$row2['lkreceb'] = 6; }
                        if($row2['obs'] == 'True'){ $row2['obs'] = 1;} else { $row2['obs'] = 0;}
                        if($row2['tipodoc'] == 'True'){ $row2['tipodoc'] = 1;} else { $row2['tipodoc'] = 0;}

                        $dataAtual = Carbon::now();
                        
                        $row2['controle'] = ($request['filial_id'] * 1000000) + $row2['controle'];

                        $dataArray2[] =
                        [
                            'Controle' => $row2['controle'],
                            'Data' => $row2['data'],
                            'filial_id' => $request['filial_id'],
                            'LkTipo' => $row2['lktipo'],
                            'Nota' => $row2['nota'],
                            'Serie' => $row2['serie'],
                            'Pedido' => $row2['pedido'],
                            'LkReceb' => $row2['lkreceb'],
                            'LkVendedor' => $row2['lkvendedor'],
                            'LkCliente' => $row2['lkcliente'],
                            'LkFornec' => $row2['lkfornec'],
                            'TagCliente' => $row2['tagcliente'],
                            'Comissao' => $row2['comissao'],
                            'ComissaoVend' => $row2['comissaovend'],
                            'Venda' => $row2['obs'],
                            'LkUser' => $row2['venda'],
                            'CFOP' => $row2['lkuser'],
                            'DataNota' => $row2['cfop'],
                            'Cancelada' => $row2['datanota'],
                            'TipoDoc' => $row2['cancelada'],
                            'Frete' => $row2['tipodoc'],
                            'ValorFrete' => $row2['frete'],
                            'LkTrans' => $row2['valorfrete'],
                            'CGI' => $row2['lktrans'],
                            'RetTrib' => $row2['cgi'],
                            'LkLoja' => $row2['rettrib'],
                            'LkCliM' => $row2['lkloja'],
                            'nfe' => $row2['lkclim'],
                            'NumCF' => $row2['nfe'],
                            'NFE_CHAVE_TEST' => $row2['numcf'],
                            'NFE_CHAVE_PROD' => $row2['nfe_chave_test'],
                            'NFE_CHAVE' => $row2['nfe_chave_prod'],
                            'NFE_AMBIENTE' => $row2['nfe_chave'],
                            'ID' => $row2['nfe_ambiente'],
                            'StatusPagamento' => $row2['id'],
                            'Revenda' => $row2['statuspagamento'],
                            'RevendaComissao' => $row2['revenda'],
                            'created_at' => $dataAtual
                        ];
                    }
                }
                if(!empty($dataArray2) && $row2['lktipo'] <> 5)
                {
                    foreach (array_chunk($dataArray2,500) as $t) {
                        MSicTabEst3A::insert($t);
                    }
                }
                if(!empty($dataImport2))
                {
                    ImportFileSic::insert($dataImport2);
                }    
                $message = $message . ", vendas importadas "; 
            }
        } else {$message = $message . ", você não informou o arquivo de Vendas";  }

        if($request->file('imported-file3'))        // Importando table de produtos vendidos. 
        {
            $path3 = $request->file('imported-file3')->getRealPath();
            $data3 = Excel::load($path3, function($reader) {})->get();

            $dataImport3[] = [
                'filial_id' => $request['filial_id'],
                'path_file' => $path3
            ];
    
            if(!empty($data3) && $data3->count())
            {
                DB::statement("SET foreign_key_checks=0");
                MSicTabEst3B::where('filial_id','=',$request['filial_id'])->delete();
                DB::statement("SET foreign_key_checks=1");

                foreach ($data3->toArray() as $row3)
                {
                    if(!empty($row3)) {

                        if (empty($row3['datainc'])) { $row3['datainc'] = "2000-01-01 00:00:00"; } else {
                            $dataHoraCSV = $row3['datainc'] . " " . "00:00:00";
                            $dt  = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV);
                            $row3['datainc'] = $dt->toDateTimeString(); 
                        }
                        if (empty($row3['previsao'])) { $row3['datainc'] = "2000-01-01 00:00:00"; } else {
                            $dataHoraCSV2 = $row3['previsao'] . " " . "00:00:00";
                            $dt2 = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV2);
                            $row3['previsao'] = $dt2->toDateTimeString(); 
                        } 
                        $dataAtual = Carbon::now();

                        $row3['controle']    = $row3['controle'] + ($request['filial_id'] * 1000000) ;
                        $row3['lkest3a']     = $row3['lkest3a'] + ($request['filial_id'] * 1000000);

                        $dataArray3[] =
                        [
                            'Controle' => $row3['controle'],
                            'filial_id' => $request['filial_id'],
                            'LkEst3A' => $row3['lkest3a'],
                            'Quantidade' => $row3['quantidade'],
                            'LkProduto' => $row3['lkproduto'],
                            'Total' => $row3['total'],
                            'TotVenda' => $row3['totvenda'],
                            'Lucro' => $row3['lucro'],
                            'Acrescimo' => $row3['acrescimo'],
                            'DataInc' => $row3['datainc'],
                            'ICMS' => $row3['icms'],
                            'QuantCanc' => $row3['quantcanc'],
                            'ValorCanc' => $row3['valorcanc'],
                            'CFOPProd' => $row3['cfopprod'],
                            'LkPrecoProd' => $row3['lkprecoprod'],
                            'ComissaoProd' => $row3['comissaoprod'],
                            'Previsao' => $row3['previsao'],
                            'created_at' => $dataAtual
                        ];
                    }
                }
                if(!empty($dataArray3))
                {
                    foreach (array_chunk($dataArray3,500) as $t) {
                        MSicTabEst3B::insert($t);
                    }
                }
                if(!empty($dataImport3))
                {
                    ImportFileSic::create($dataImport3);
                }    
                $message = $message . "e produtos vendidos importados com sucesso!"; 
            }
           
        } else {
            $message = $message . ", você não informou o arquivo de produtos vendidos";  
        }
        /**
         *
         * Importando arquivo de NFC-e - Arquivos dos cupons fiscais eletrônicos do SIC a fim de atender à contabilidade. 
         * 
         */
        if($request->file('imported-file4'))        // Importando table de NFC-e. 
        {
            $path4 = $request->file('imported-file4')->getRealPath();
            $data4 = Excel::load($path4, function($reader) {})->get();

            $dataImport4[] = [
                'filial_id' => $request['filial_id'],
                'path_file' => $path4
            ];
    
            if(!empty($data4) && $data4->count())
            {
                DB::statement("SET foreign_key_checks=0");
                MSicTabNFCe::where('filial_id','=',$request['filial_id'])->delete();
                

                foreach ($data4->toArray() as $row4)
                {
                    if(!empty($row4)) {

                        if (empty($row4['datahora'])) { $row4['datahora'] = "2000-01-01 00:00:00"; } else {
                            $dataHoraCSV = $row4['datahora'];
                            $dt  = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV);
                            $row4['datahora'] = $dt->toDateTimeString(); 
                        }
                        if (empty($row4['recibo'])) { $row4['recibo'] = "2000-01-01 00:00:00"; } else {
                            $dataHoraCSV2 = $row4['recibo'];
                            $dt2 = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV2);
                            $row4['recibo'] = $dt2->toDateTimeString(); 
                        } 
                        if (empty($row4['emitida'])) { $row4['emitida'] = "2000-01-01 00:00:00"; } else {
                            $dataHoraCSV3 = $row4['emitida'];
                            $dt3 = Carbon::createFromFormat('d/m/Y H:i:s', $dataHoraCSV3);
                            $row4['emitida'] = $dt3->toDateTimeString(); 
                        } 
                        $dataAtual = Carbon::now();

                        $row4['controle']    = $row4['controle'] + ($request['filial_id'] * 1000000) ;
                        $row4['lkest3a']     = $row4['lkest3a'] + ($request['filial_id'] * 1000000);

                        $dataArray4[] =
                        [
                            'Controle' => $row4['controle'],
                            'filial_id' => $request['filial_id'],
                            'LkEst3A' => $row4['lkest3a'],
                            'DataHora' => $row4['datahora'],
                            'Numero' => $row4['numero'],
                            'Serie' => $row4['serie'],
                            'Chave' => $row4['chave'],
                            'Ambiente' => $row4['ambiente'],
                            'Recibo' => $row4['xml'],
                            'Emitida' => $row4['recibo'],
                            'Cancelada' => $row4['emitida'],
                        ];
                    }
                }
                if(!empty($dataArray4))
                {
                    foreach (array_chunk($dataArray4,500) as $t) {
                        MSicTabNFCe::insert($t);
                    }
                }
                if(!empty($dataImport4))
                {
                    ImportFileSic::create($dataImport4);
                }    
                DB::statement("SET foreign_key_checks=1");
                $message = $message . "e NFC-e importadas com sucesso!"; 
            }
            return redirect()->back()->with('success', $message);
        } else {
            $message = $message . ", você não informou o arquivo de Nfc-e";  
        }
    }


    public function importTabEst8(Request $request) //Setores
    {
        
        if($request->file('imported-file'))
        {
            $path = $request->file('imported-file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();

            $dataImport[] = [
                'path_file' => $path
            ];
    
            if(!empty($data) && $data->count())
            {
                DB::statement("SET foreign_key_checks=0");
                Setores::truncate();
                DB::statement("SET foreign_key_checks=1");
                
                foreach ($data->toArray() as $row)
                {
                    if(!empty($row))
                    {
                        // Começando os testes dos dados para ajustar ao DB Atual
                        
                        $dataAtual = Carbon::now();

                        $dataArray[] =
                        [
                            'Controle' => $row['controle'],
                            'Setor' => $row['setor'],
                            'created_at' => $dataAtual
                        ];
                    }
                }
                if(!empty($dataArray))
                {
                    Setores::insert($dataArray);
                }
                if(!empty($dataImport))
                {
                    ImportFileSic::create($dataImport);
                }    
                return back();
            }
        }
    }
}