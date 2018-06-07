@extends('adminlte::page') 

@section('title', 'Painel') 

@section('content_header')

d<h1>
    DashBoard Filiais 
    @if (isset($periodo))
        <small>Vendedores {{$periodo}}</small>
    @else 
        <small>Vendedores {{$periodo}}</small>
    @endif
</h1>
    <div class="form-group col-md-12">
        <a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
            <span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
    </div>
    
@stop 

@section('content')
    <div class="row"> 
        @php
        $posicao = 0;
        @endphp
        @foreach($filiais as $valor => $valor2)
        @php
        ++$posicao;
        @endphp
        <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="info-box bg-green" >
            <span class="info-box-icon">{{$posicao}}º</span>

            <div class="info-box-content">
            <span class="info-box-text">{{$valor}}</span>
            <span class="info-box-number">R$ {{number_format($valor2,2,',','.')}}</span>

            <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
            </div>
                <span class="progress-description">
                    100% 
                </span>
            </div>
            <a href="#" class="small-box-footer">Mais informações<i class="fa fa-arrow-circle-right"></i></a>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>
        @endforeach
    </div>

@component('painel.modals.modal_primary')
	@slot('icoBtnModal')
		glyphicon glyphicon-plus
	@endslot
	@slot('txtBtnModal')
		Importar do SIC
	@endslot
	@slot('triggerModal')
		b6
	@endslot
	@slot('tituloModal')
		Selecione o Periodo...
	@endslot
	@slot('actionModal')
        HomeController@dashboard_filiais
	@endslot
	@slot('methodModal')
		get
	@endslot

	@slot('bodyModal')
	<div class="form-group col-md-4">
		<label>Data Inicial</label>
		<input class="form-control" type="date" name="initial_date" value="{{ Carbon\Carbon::now()->format('d-m-Y')}}" />
	</div>
	<div class="form-group col-md-4">
		<label>Data Final</label>
		<input class="form-control" type="date" name="final_date" value="{{ Carbon\Carbon::now()->format('d-m-Y')}}" />
	</div>
	<div class="form-group col-md-4">
		<label>% de comissão do chip </label>
		<input class="form-control" type="number" name="porcComissaoChip" value="25" />
	</div>
	@endslot
	@slot('btnConfirmar')
		Filtrar
	@endslot
    @endcomponent

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
<script type="text/javascript">
    $(document).ready(function(){
        $("#btnModal6").click(function(){
            $("#b6").modal('show');
        });
    });
</script>
    
@stop