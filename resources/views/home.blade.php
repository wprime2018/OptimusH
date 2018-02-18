@extends('adminlte::page') 

@section('title', 'Painel') 

@section('content_header')

    <h1>Painel de Controle</h1>
@stop 

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6"> <!-- Produtos -->
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{$lblTotProdutos}}</h3>

                    <p>Produtos Cadastrados</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">Mais Informações
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6"> <!-- Produtos perto do mínimo -->
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$countProdWarning}} - {{$porcProdWarning}}%</h3>
                    <p>Produtos</br>Estão Chegando no estoque mínimo</p>

                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">Mais Informações
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6"> <!-- Produtos pra comprar Urgente -->
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{$countProdDanger}} - {{$porcProdDanger}}%</h3>

                    <p>Produtos</br>Precisam de reposição de Estoque</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{route('PedComprarTotal')}}" class="small-box-footer">Mais Informações
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6"> <!-- Produtos Que não venderam no período calculado no estoque -->
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{$countProdNoVend}} - {{$porcProdNoVend}}%</h3>

                    <p>Produtos</br>Não venderam no período calculado.</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{route('NaoVendidos')}}" class="small-box-footer">Mais Informações
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6"> <!-- Filiais -->
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{$lblTotFilial}}</h3>

                    <p>Filiais Cadastradas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{route('DashFiliais')}}" class="small-box-footer">Mais Informações
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6"> <!-- Despesas X Faturamentos -->
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>29
                        <sup style="font-size: 20px">%</sup>
                    </h3>

                    <p>Despesas X Faturamento</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">Mais Informações
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>    
    <div class="col-md-6"> <!-- Gráfico Despesas X Faturamentos -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Despesas X Faturamento</h3>

                <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="pieChart" style="height: 308px; width: 616px;" width="770" height="385"></canvas>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-6"> <!-- Gráfico Produtos X Pedidos -->
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Produtos X Pedidos</h3>

                <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChartP" style="height: 308px; width: 616px;" width="770" height="385"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <ul class="chart-legend clearfix">
                            <li><i class="fa fa-circle" style="color: #9DBCE1;font-size:160%;"></i> Restante dos Produtos</li>
                            <li><i class="fa fa-circle" style="color: #EFBC9B;font-size:160%;"></i> Chegando no mínimo</li>
                            <li><i class="fa fa-circle" style="color: #875181;font-size:160%;"></i> Comprar Urgente!</li>
                            <li><i class="fa fa-circle" style="color: #424B54;font-size:160%;"></i> Produtos que Não Vendem</li>
                        </ul>
                    </div>
                <!-- /.col -->
                </div>
            </div>
            <!-- /.box-body -->
        </div>
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
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
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
    var fatiaProdDanger = {{$fatiaProdDanger}}
    var fatiaProdWarning = {{$fatiaProdWarning}}
    var fatiaProdNoVend = {{$fatiaProdNoVend}}
    var fatiaRestante   = {{$fatiaRestante}}

    var pieChartCanvasP = $('#pieChartP').get(0).getContext('2d')
    var pieChartP       = new Chart(pieChartCanvasP)
    var pieOptionsP     = pieOptions
    var PieDataP        = [
    {
        value    :  fatiaRestante,
        color    : '#9DBCE1',
        highlight: '#9DBCE1',
        label    : 'Restante dos Produtos'
        },
        {
        value    :  fatiaProdWarning,
        color    : '#EFBC9B',
        highlight: '#EFBC9B',
        label    : 'Chegando no mínimo'
        },
        {
        value    :  fatiaProdDanger,
        color    : '#875181',
        highlight: '#875181',
        label    : 'Comprar Urgente!'
        },
        {
        value    :  fatiaProdNoVend,
        color    : '#424B54',
        highlight: '#424B54',
        label    : 'Produtos que Não Vendem'
        }
    ]
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChartP.Doughnut(PieDataP, pieOptionsP)





})


</script>
        
@stop