@extends('adminlte::page') 

@section('title', 'Painel') 

@section('content_header')

    <h1>Painel de Controle</h1>
@stop 

@section('content')
    <div class="row"> 
        @foreach($Filiais as $f)
        @isset($totRecebPorFilial[$f->id])
        <div class="col-md-3"> <!-- Gráfico Despesas X Faturamentos -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$f->codigo}} - {{$f->fantasia}}</h3>
                        
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <!--<div class="box-body" style="">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="pieChart{{$f->codigo}}" height="202" width="305" style="width: 244px; height: 162px;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fa fa-circle-o text-red"></i>Teste</li>
                            </ul>
                        </div>
                    </div>
                </div>-->
                <!-- /.box-body -->
                <div class="box-footer no-padding" style="">
                    <ul class="nav nav-pills nav-stacked">
                        @foreach($totRecebPorFilial as $filial => $forma)
                            @if ($filial == $f->id)
                                <?php $total = 0 ?>
                                @foreach($forma as $Receb => $valor)
                                    <li><a href="#">{{$Receb}}<span class="pull-right text-blue"><!--<i class="fa fa-angle-down"></i>-->R$ {{number_format($valor,2,',','.')}}</span></a></li>
                                    <?php $total = $total + $valor?>
                                @endforeach
                                <li><a href="#"><h4>Total de Vendas<span class="pull-right text-blue"><!--<i class="fa fa-angle-down"></i>--><b>R$ {{number_format($total,2,',','.')}}</b></h4></span></a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- /.footer -->
            </div>
        </div>
        @endisset
        @endforeach
    </div>
@stop

@section('js')
<script>
    $(function () {
    /* ChartJS
        * -------
        * Here we will create a few charts using ChartJS
        */

    //-------------
    //- PIE CHART - Despesas X Faturamento
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart1').get(0).getContext('2d')
    var pieChart       = new Chart(pieChartCanvas)
    var PieData        = [
    {
        value    : 1846,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Faturamento'
        },
        {
        value    : 754,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'Despesas'
        }
    ]
    var pieOptions     = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke
    segmentStrokeWidth   : 2,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps       : 100,
    //String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : true,
    //String - A legend template
    legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%> <li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li> <%}%></ul>'
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions)


    //-------------
    //- PIE CHART - Produtos e Pedidos
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvasP = $('#pieChartP').get(0).getContext('2d')
    var pieChartP       = new Chart(pieChartCanvasP)
    var pieOptionsP     = pieOptions
    var PieDataP        = [
    {
        value    : 364,
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Produtos'
        },
        {
        value    : 762,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'Chegando no mínimo'
        },
        {
        value    : 1126,
        color    : '#dd4b39',
        highlight: '#dd4b39',
        label    : 'Comprar Urgente!'
        }
    ]
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChartP.Doughnut(PieDataP, pieOptionsP)

})


</script>
        
@stop