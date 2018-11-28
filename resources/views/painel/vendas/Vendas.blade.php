@extends('adminlte::page') 

@section('title', 'Vendas') 

@section('content_header')

<h1>
	Vendas
	<small>Importadas</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Vendas</a>
	</li>
	<li>
		<a href="#">Importadas</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box box-info">
	<div class="box-header">
		<h3 class="box-title">Importações</h3>
	</div>
	<div class="box-body">
		@include('painel.includes.alerts')
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b1" id="btnModal1" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-shopping-cart"></span> Vendas</a>
			<a data-toggle="modal" data-target="b2" id="btnModal2" class="btn btn-warning btn-lg active btn-add">
				<span class="glyphicon glyphicon-user"></span> Vendedores</a>
			<a data-toggle="modal" data-target="b3" id="btnModal3" class="btn btn-danger btn-lg active btn-add">
				<span class="glyphicon glyphicon-fees-payments"></span> Tipos de Pagamentos</a>
			<a data-toggle="modal" data-target="b5" id="btnModal5" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-database-search"></span>Setores</a>
			<a data-toggle="modal" data-target="b4" id="btnModal4" class="btn btn-success active btn-lg btn-add">
				<span class="glyphicons glyphicons-cargo"></span>Calcular Estoque</a>
		</div>
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
		</div>
	</div>
</div>
<div class="box box-info">
	<div class="box-header">
		<h3 class="box-title">Vendas por Forma de Pagamento no período de:@if(isset($dados)) ? {{$periodo}}@endif </h3>
	</div>
	<div class="box-body">
		@if (isset($dados['recebim']))
			@include('painel.vendas.vendas01')
		@endif
	</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Ranking Vendas no período de:@if(isset($dados)) ? {{$periodo}}@endif </h3>
	</div>
	<div class="box-body">
		@if (isset($dados['recebim']))
			@include('painel.vendas.vendas02')
		@endif
	</div>
</div>
	
	@component('painel.modals.modal_primary')
		@slot('txtBtnModal')
			Importar do SIC
		@endslot
		@slot('triggerModal')
			b1
		@endslot
		@slot('tituloModal')
			Importar Vendas SIC (TabEst1 + TabEst3A + TabEst3B + TabNFCe)
		@endslot
		@slot('actionModal')
			Painel\SicTabEst1Controller@importVendas
		@endslot
		@slot('methodModal')
			post
		@endslot
		@slot('bodyModal')
			<div class='row'>	
				<div class="form-group col-md-3">  <!-- testando tudo -->
					<label>Filial</label>
					<select name="filial_id" class="form-control">
						@if( isset($Despesas) ) 
							@foreach($Filiais as $value)
								<option <?=("{{$value->id}}")? 'selected' : ''?>value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
							@endforeach
						@else 
							<option selected="disabled">Selecionar</option>
							@foreach($Filiais as $value)
								<option value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class='row'>	
				<div class="col-md-6">
					<label>TabEst1</label>
					<input type="file" name="imported-file1"/>
				</div>
			</div>
			<div class='row'>	
				<div class="col-md-6">
					<label>TabEst3A</label>
					<input type="file" name="imported-file2"/>
				</div>
			</div>
			<div class='row'>	
				<div class="col-md-6">
					<label>TabEst3B</label>
					<input type="file" name="imported-file3"/>
				</div>
			</div>
			<div class='row'>	
				<div class="col-md-6">
					<label>TabNFCe</label>
					<input type="file" name="imported-file4"/>
				</div>
			</div>
		@endslot
		@slot('btnConfirmar')
			Importar
		@endslot
	@endcomponent

	@component('painel.modals.modal_primary')
		@slot('txtBtnModal')
			Importar do SIC
		@endslot
		@slot('triggerModal')
			b2
		@endslot
		@slot('tituloModal')
			Importar Vendedores (TabVend)
		@endslot
		@slot('actionModal')
			Painel\SicTabEst1Controller@importTabVend
		@endslot
		@slot('methodModal')
			post
		@endslot
		@slot('bodyModal')
			<div class="col-md-6">
				<div class="row">
					<input type="file" name="imported-file"/>
				</div>
			</div>
		@endslot
		@slot('btnConfirmar')
			Importar
		@endslot
	@endcomponent

	@component('painel.modals.modal_primary')
	@slot('txtBtnModal')
		Importar do SIC
	@endslot
	@slot('triggerModal')
		b3
	@endslot
	@slot('tituloModal')
		Importar Tipos de Recebimentos SIC (TabEst7)
	@endslot
	@slot('actionModal')
		Painel\SicTabEst1Controller@importTabEst7
	@endslot
	@slot('methodModal')
		post
	@endslot
	@slot('bodyModal')
		<div class="col-md-6">
			<div class="row">
				<input type="file" name="imported-file"/>
			</div>
		</div>
	@endslot
	@slot('btnConfirmar')
		Importar
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
		b4
	@endslot
	@slot('tituloModal')
		Calculando o estoque...
	@endslot
	@slot('actionModal')
		Painel\PedidosEstoque@calculaEstoque
	@endslot
	@slot('methodModal')
		post
	@endslot

	@slot('bodyModal')
	<div class="form-group col-md-4">
		<label>Período Vendas</label>
		<select class="form-control" name="week_vendas">
			<option value="7">1 Semana</option>
			<option value="14">2 Semanas</option>
			<option value="21">3 Semanas</option>
			<option value="30">1 Mês</option>
		</select>
	</div>
	@endslot
	@slot('btnConfirmar')
		Calcular
	@endslot
	@endcomponent

	@component('painel.modals.modal_primary')
	@slot('txtBtnModal')
		Importar do SIC
	@endslot
	@slot('triggerModal')
		b5
	@endslot
	@slot('tituloModal')
		Importar Setores dos Produtos SIC (TabEst8)
	@endslot
	@slot('actionModal')
		Painel\SicTabEst1Controller@importTabEst8
	@endslot
	@slot('methodModal')
		post
	@endslot
	@slot('bodyModal')
		<div class="col-md-6">
			<div class="row">
				<input type="file" name="imported-file"/>
			</div>
		</div>
	@endslot
	@slot('btnConfirmar')
		Importar
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
		Painel\Vendas@index_vendas_pgto
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
	@endslot
	@slot('btnConfirmar')
		Filtrar
	@endslot
	@endcomponent

@stop
@section ('js')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#btnModal1").click(function(){
				$("#b1").modal('show');
			});
			$("#btnModal2").click(function(){
				$("#b2").modal('show');
			});
			$("#btnModal3").click(function(){
				$("#b3").modal('show');
			});
			$("#btnModal4").click(function(){
				$("#b4").modal('show');
			});
			$("#btnModal5").click(function(){
				$("#b5").modal('show');
			});
			$("#btnModal6").click(function(){
				$("#b6").modal('show');
			});
		});
	</script>
@stop