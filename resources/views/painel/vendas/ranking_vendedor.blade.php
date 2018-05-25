@extends('adminlte::page') 

@section('title', 'Vendas') 

@section('content_header')

<h1>
	Ranking 
	<small>Vendedores</small>
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
<div class="form-group col-md-12">
	<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
		<span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
</div>

@stop 

@section('content')
<div class="box-body">
	@php $num_filial = 0;@endphp
	@foreach($formas as $filiais => $vendedores)	

	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">{{$filiais}}</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		
		</div>
		<!-- /.box-header -->
		<div class="box-body">

		<div class="table-responsive">
			@php 
				$num_filial = $num_filial + 1;
				$string_filial = str_replace(" ", "", $filiais);
			@endphp
			<table id="example1{{$num_filial}}" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
				<thead>
					<tr role="row">
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Vendedor</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Vendas</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Qtde Vendas</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Ticket Médio</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Crédito</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Débito</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Dinheiro</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Comissão</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vendedores as $nomes => $valores)
						<tr role="row" class="odd" id="{{$nomes}}">
							<td>{{$nomes}}</td>
							@foreach($valores as $tipos => $valor)
								<td align="right">{{$valor}}</td>
							@endforeach
					@endforeach
						</tr>
				</tbody>
				<tfoot>
					<tr>
						<th rowspan="1" colspan="1">Totais</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<!-- /.table-responsive -->
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
		Painel\Vendas@ranking_vendedores
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
	@endslot
	@slot('btnConfirmar')
		Filtrar
	@endslot
@endcomponent
@stop

@section ('js')

	<script src="{{ asset('js/Painel/config_datatables.js') }}"> </script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#btnModal6").click(function(){
				$("#b6").modal('show');
			});
		});
	</script>
@stop