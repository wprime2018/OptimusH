<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Painel\FunctionsController;
use App\Http\Controllers\Painel\FiliaisController;
use DB;
use Carbon\Carbon;

class MainController extends Controller
{
    
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
        $filiais    = FiliaisController::search_filiais();
        $cores      = FunctionsController::array_cores();

        $dateAtual  = Carbon::now()->subMonth();
        $data1      = $dateAtual->startOfMonth()->toDateTimeString();
        $data2      = $dateAtual->endOfMonth()->toDateTimeString();
        $dados      = FunctionsController::vendas_pgto($data1,$data2);

        foreach ($dados['gt'] as $user) {
            $totais[] = $user['Total'];
        }
        array_multisort($totais, SORT_DESC, $dados['gt']);        

        $pieData1    = Array();
        $i = 0;    
        foreach ($dados['gt'] as $gt => $valor) {
            if ($i > 11)
                $i = 1;
            $perc = ($valor['Total'] / $dados['gt3']['Total']) * 100;

            array_push($pieData1,[
                'value'     => $perc,
                'color'     => $cores[$i],
                'highlight' => $cores[$i],
                'label'     => $gt
            ]);
            ++$i;
        }
        $filiais = $dados['gt'];
        $gt = $dados['gt3'];
        /*////////////////////////////////////////
        //// Calculando o gráfixo Vendas X NFCe
        ///////////////////////////////////////*/
        $pieData2    = Array();
        $perc2 = ($gt['NFCe'] / $gt['Total']) * 100;
        $perc1 = 100 - $perc2;

        array_push($pieData2,[
            'value'     => $perc1,
            'color'     => $cores[0],
            'highlight' => $cores[0],
            'label'     => 'Vendas'
        ]);
        array_push($pieData2,[
            'value'     => $perc2,
            'color'     => $cores[1],
            'highlight' => $cores[1],
            'label'     => 'NFCe'
        ]);
        /*////////////////////////////////////////
        //// Calculando o gráfixo Tipo de PGTO
        ///////////////////////////////////////*/
        $pieData3    = Array();
        $perc2 = ($gt['Din'] / $gt['Total']) * 100;
        $perc3 = ($gt['Cred'] / $gt['Total']) * 100;
        $perc4 = ($gt['Deb'] / $gt['Total']) * 100;
        $perc1 = 100 - $perc4 - $perc3 - $perc2 ;

        array_push($pieData3,[
            'value'     => $perc2,
            'color'     => $cores[0],
            'highlight' => $cores[0],
            'label'     => 'Din'
        ]);
        array_push($pieData3,[
            'value'     => $perc3,
            'color'     => $cores[1],
            'highlight' => $cores[1],
            'label'     => 'Cred'
        ]);
        array_push($pieData3,[
            'value'     => $perc4,
            'color'     => $cores[2],
            'highlight' => $cores[3],
            'label'     => 'Deb'
        ]);


        $pieFData1 = FunctionsController::ArrayPhpToJs($pieData1);
        $pieFData2 = FunctionsController::ArrayPhpToJs($pieData2);
        $pieFData3 = FunctionsController::ArrayPhpToJs($pieData3);

        return view('painel.dashboard.main',compact('filiais',
                                                    'gt',
                                                    'cores',
                                                    'pieFData1',
                                                    'pieFData2',
                                                    'pieFData3'));
    }
}
