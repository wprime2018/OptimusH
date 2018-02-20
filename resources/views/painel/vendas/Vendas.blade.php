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
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Vendas Importadas</h3>
	</div>
	<!-- /.box-header -->

	<div class="box-body">
		@include('painel.includes.alerts')
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b1" id="btnModal1" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Importar Vendas</a>
			<a data-toggle="modal" data-target="b2" id="btnModal2" class="btn btn-warning btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Importar Vendedores</a>
			<a data-toggle="modal" data-target="b3" id="btnModal3" class="btn btn-danger btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Importar Tipos de Recebimentos</a>
			<a data-toggle="modal" data-target="b4" id="btnModal4" class="btn btn-success active btn-lg btn-add">
				<span class="glyphicon glyphicon-plus"></span>Calcular Estoque</a>
	
	
		</div>
		<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Filial</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Data e Hora</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 400px;">Vendedor</th>
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
						style="width: 150.0px;">Valor Comissão</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 100px;">Tipo de Recebimento</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 100px;">Preço de Venda</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 187.4px;">Nota Fiscal</th>
				</tr>
			</thead>
			<tbody>
				@foreach($Vendas as $venda)
				<tr role="row" class="odd" id="{{$venda->id}}">
					<td class="sorting_1">{{$venda->fantasia}}</td>
					<td>{{$venda->Data}}</td>
					<td>{{$venda->Nome}}</td>
					<td align="right">% {{number_format($venda->ComissaoVend, 2, ',', '.')}}</td>
					<td>{{$venda->Recebimento}}</td>
					<td align="right">R$ {{number_format($venda->PrecoVenda, 2, ',', '.')}}</td>
					<td>{{$venda->DataInc}}</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th rowspan="1" colspan="1">Filial</th>
					<th rowspan="1" colspan="1">Data e Hora</th>
					<th rowspan="1" colspan="1">Vendedor</th>
					<th rowspan="1" colspan="1">Valor Comissão</th>
					<th rowspan="1" colspan="1">Tipo de Recebimento</th>
					<th rowspan="1" colspan="1">Preço de Venda</th>
					<th rowspan="1" colspan="1">Data de Cadastro</th>
				</tr>
			</tfoot>
		</table>
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
			Importar Vendas SIC (TabEst1 + TabEst3A + TabEst3B)
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
						@foreach($ListFiliais as $value)
							<option <?=("{{$value->id}}")? 'selected' : ''?>value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
						@endforeach
					@else 
						<option selected="disabled">Selecionar</option>
						@foreach($ListFiliais as $value)
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
		<label>Data Inicial</label>
		<input type="date" name="initial_date" value="{{ \Carbon\Carbon::now()->format('d-m-Y')}}" />
	</div>
	<div class="form-group col-md-4">
		<label>Data Final</label>
		<input type="date" name="final_date" value="{{ \Carbon\Carbon::now()->format('d-m-Y')}}" />
	</div>
	@endslot
	@slot('btnConfirmar')
		Calcular
	@endslot
	@endcomponent
@stop
@section ('js')
	<script src="{{ asset('js/Painel/config_datatables.js') }}"> </script>
	
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
		});
	</script>
@stop