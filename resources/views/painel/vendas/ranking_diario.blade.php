@extends('adminlte::page') 

@section('title', 'Vendas') 

@section('content_header')

<h1>
	Vendas
	<small>Diárias</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#table_r_filiais">
			<i class="fa fa-dashboard"></i> Vendas</a>
	</li>
	<li>
		<a href="#table_r_filiais">Resumo diário</a>
	</li>
</ol>
<div class="row">
	<div class="form-group col-md-12">
		<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
			<span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
	</div>
</div>

@stop 

@section('content')

	<input id="mesChanged" name="mesChanged" type="hidden" value="0">
	<input id="anoChanged" name="anoChanged" type="hidden" value="0">

	@component('painel.boxes.box')
		@slot('boxtitle')
			@if (isset($dados['periodo']))
				<h3 class="box-title">Período: {{$dados['periodo']}}</h3>
			@else 
				<h3 class="box-title">Clique no botão acima e selecione os dados</h3>
			@endif
		@endslot
		@slot('boxbody')
			@include('painel.vendas.rankingDiario01')
		@endslot
		@slot('boxfooter')
		@endslot
	@endcomponent

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
		Painel\Vendas@ranking_diario
	@endslot
	@slot('methodModal')
		get
	@endslot

	@slot('bodyModal')
		<div class="form-group col-md-4">
			<label>Selecione o mês</label>
			<select class="form-control" name="month_date">
				@foreach ($minMaxVendas['periodo'] as $meses)
					@if (isset($meses['mes']))
						<option value="{{str_pad($meses['mes'], 2, "0", STR_PAD_LEFT)}}-{{$meses['ano']}}">{{$meses['extenso']}} </option>
					@endif
				@endforeach
			</select>
		</div>
	@endslot
	@slot('btnConfirmar')
		Filtrar
	@endslot
	@endcomponent
@stop

@section ('js')

	<script type="text/javascript">
		$(document).ready(function(){
			$("#btnModal6").click(function(){
				$("#b6").modal('show');
			});
		});
	</script>
	<script>
		// Tabela das filiais resumidas. 
		$(function () {
			$('#table_r_filiais').DataTable({
				'fixedHeader' : true,
				'lengthChange': true,
				'ordering'    : true,
				'info'        : true,
				'autoWidth'   : true,
				'responsive'  : true,
				'dom': '<l<B>f<t>ip>',
				'buttons': [
					'excelHtml5',
					'csvHtml5',
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4',
						footer: true,
						title: 'OptimusH - Ranking de Vendas diário'
					}
		
				]
			})
		})
	</script>

@stop