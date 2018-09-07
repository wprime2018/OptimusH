@extends('adminlte::page') 

@section('title', 'NFCe') 

@section('content_header')

<h1>
	NFCe
	<small>Emitidas</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i>NFCe</a>
	</li>
	<li>
		<a href="#">Emitidas</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box">
	<div class="box-header">
		@if (isset($dados['TotalComNF']))
			<h3 class="box-title">{{$dados['filial_changed']}} - {{$dados['Periodo']}}</h3>
		@endif
	</div>
	<!-- /.box-header -->

	<div class="box-body">
		@include('painel.includes.alerts')
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-filter"></span>Selecionar Filial</a>
		</div>
		@if (isset($dados['TotalComNF']))
			@include('painel.vendas.nfce1')
		@endif
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
		Painel\Vendas@nfce
	@endslot
	@slot('methodModal')
		get
	@endslot

	@slot('bodyModal')
	<div class="row">
		<div class="form-group col-md-3">  <!-- testando tudo -->
			<label>Filial</label>
			<select name="filial_id" class="form-control">
				<option selected="disabled">Selecionar</option>
				@foreach($ListFiliais as $value)
					<option value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group col-md-4">
			<label>Data Inicial</label>
			{!! Form::date('initial_date',\Carbon\Carbon::now()->firstOfMonth(),['class' => 'form-control', 'id'=>"initial_date"]) !!}
		</div>
		<div class="form-group col-md-4">
			<label>Data Final</label>
			{!! Form::date('final_date',\Carbon\Carbon::now(),['class' => 'form-control', 'id'=>"final_date"]) !!}
		</div>
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