@extends('adminlte::page') 

@section('title', 'Vendas') 

@section('content_header')

<h1>
	Ranking 
	@if (isset($carbonData1))
		<small>Vendedores de {{$carbonData1->format('d/m/Y')}} até {{$carbonData2->format('d/m/Y')}}</small>
	@else 
		<small>Vendedores de {{$carbonData1->format('d/m/Y')}} até {{$carbonData2->format('d/m/Y')}}</small>
	@endif
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
		
			<i class="fa fa-dashboard"></i> Vendas</a>
	</li>
	<li>
		<a href="#">Importados</a>
	</li>
</ol>
<div class="form-group col-md-12">
	<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
		<span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
</div>

@stop 

@section('content')
<div class="box-body">
	@include('painel.includes.alerts')
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">{{$filiais}}</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
			@include('painel.vendas.rankingVend01')
		</div>
	</div>
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
		Painel\Vendas@ranking_vendedores
	@endslot
	@slot('methodModal')
		get
	@endslot

	@slot('bodyModal')
	<div class="form-group col-md-4">
		<label>Data Inicial</label>
		{!! Form::date('initial_date',\Carbon\Carbon::now()->firstOfMonth(),['class' => 'form-control', 'id'=>"initial_date"]) !!}
	</div>
	<div class="form-group col-md-4">
		<label>Data Final</label>
		{!! Form::date('final_date',\Carbon\Carbon::now(),['class' => 'form-control', 'id'=>"final_date"]) !!}
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

@section ('js')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#btnModal6").click(function(){
				$("#b6").modal('show');
			});
		});
	</script>
@stop