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
		<h3 class="box-title">Importações</h3>
	</div>
	<!-- /.box-header -->

	<div class="box-body">
		@include('painel.includes.alerts')
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b1" id="btnModal1" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Vendas</a>
			<a data-toggle="modal" data-target="b2" id="btnModal2" class="btn btn-warning btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Vendedores</a>
			<a data-toggle="modal" data-target="b3" id="btnModal3" class="btn btn-danger btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Tipos de Pagamentos</a>
			<a data-toggle="modal" data-target="b5" id="btnModal5" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Setores</a>
			<a data-toggle="modal" data-target="b4" id="btnModal4" class="btn btn-success active btn-lg btn-add">
				<span class="glyphicon glyphicon-plus"></span>Calcular Estoque</a>
		</div>
	</div>
	<div class="box-body"
		<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Descrição</th>
					@foreach($Filiais as $f)	
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">{{$f->codigo}}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($TipoRecebimentos as $Tr)	
					<tr role="row" class="odd" id="{{$Tr->Recebimento}}">
						<td class="sorting_1">{{$Tr->Recebimento}}</td>
						@foreach($Filiais as $f)
							<td>R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Total"],2,',','.')}}</td>
						@endforeach
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr >
					<th rowspan="1" colspan="1"><font color="red">Crédito</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="red">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Cred"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr>
					<th rowspan="1" colspan="1"><font color="orange">Débito</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="orange">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Deb"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr>
					<th rowspan="1" colspan="1"><font color="blue">Total de Vendas</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TotalVendas"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr>
					<th rowspan="1" colspan="1"><font color="pink">Ticket Médio</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="pink">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TicketM"],2,',','.')}}</th>
					@endforeach
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
	  <h3 class="box-title">Ranking Vendas</h3>

	  <div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		</button>
		<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	  </div>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
	  <div class="table-responsive">
		<table class="table no-margin">
		  <thead>
		  <tr>
			<th>Filial</th>
			<th>Vendas</th>
		  </tr>
		  </thead>
		  <tbody>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR9842</a></td>
			<td>Call of Duty IV</td>
			<td><span class="label label-success">Shipped</span></td>
			<td>
			  <div class="sparkbar" data-color="#00a65a" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR1848</a></td>
			<td>Samsung Smart TV</td>
			<td><span class="label label-warning">Pending</span></td>
			<td>
			  <div class="sparkbar" data-color="#f39c12" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR7429</a></td>
			<td>iPhone 6 Plus</td>
			<td><span class="label label-danger">Delivered</span></td>
			<td>
			  <div class="sparkbar" data-color="#f56954" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR7429</a></td>
			<td>Samsung Smart TV</td>
			<td><span class="label label-info">Processing</span></td>
			<td>
			  <div class="sparkbar" data-color="#00c0ef" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR1848</a></td>
			<td>Samsung Smart TV</td>
			<td><span class="label label-warning">Pending</span></td>
			<td>
			  <div class="sparkbar" data-color="#f39c12" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR7429</a></td>
			<td>iPhone 6 Plus</td>
			<td><span class="label label-danger">Delivered</span></td>
			<td>
			  <div class="sparkbar" data-color="#f56954" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  <tr>
			<td><a href="pages/examples/invoice.html">OR9842</a></td>
			<td>Call of Duty IV</td>
			<td><span class="label label-success">Shipped</span></td>
			<td>
			  <div class="sparkbar" data-color="#00a65a" data-height="20"><canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas></div>
			</td>
		  </tr>
		  </tbody>
		</table>
	  </div>
	  <!-- /.table-responsive -->
	</div>
	<!-- /.box-body -->
	<div class="box-footer clearfix">
	  <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
	  <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
	</div>
	<!-- /.box-footer -->
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
			$("#btnModal5").click(function(){
				$("#b5").modal('show');
			});
		});
	</script>
@stop