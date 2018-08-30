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
		<h3 class="box-title">Importações</h3>
	</div>
	<!-- /.box-header -->

	<div class="box-body">
		@include('painel.includes.alerts')
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-filter"></span>Selecionar Filial</a>
		</div>
	
		<div class="box-body"
		<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Número</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Data</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Chave</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Forma PGTO</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Valor R$</th>
				</tr>
			</thead>
			<tbody>
				@foreach($Vendas as $V)	
					<tr role="row" class="odd" id="{{$V->nfce->Numero}}">
						<td class="sorting_1">{{$V->nfce->Numero}}</td>
						<td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$V->nfce->Emitida)->format('d/m/Y H:i:s')}}</td>
						<td>{{$V->nfce->Chave}}</td>
						<td>{{$V->Receb->Recebimento}}</td>
						<td>{{number_format($V->prodVendidos->sum('Total'),2,',','.')}}</td>
					</tr>
				@endforeach
			</tbody>	
			<tfoot>
				<th rowspan="1" colspan="1"><font color="blue">Total de NFCe´s</th>
				<th rowspan="1" colspan="2"><font color="blue">R$ {{number_format($tot_vendas,2,',','.')}}</th>
				<th rowspan="1" colspan="1"><font color="blue">Quantidade de NFCe´s</th>
				<th rowspan="1" colspan="2"><font color="blue">{{number_format($qtde_vendas,0,',','.')}}</th>
			</tfoot>
		</table>
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
		Painel\Vendas@nfce
	@endslot
	@slot('methodModal')
		get
	@endslot

	@slot('bodyModal')
	<div class='row'>	
		<div class="form-group col-md-3">  <!-- testando tudo -->
			<label>Filial</label>
			<select name="filial_id" class="form-control">
				<option selected="disabled">Selecionar</option>
				@foreach($ListFiliais as $value)
					<option value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
				@endforeach
			</select>
		</div>
	</div>
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
	<script type="text/javascript">
		$(document).ready(function(){
			$("#btnModal6").click(function(){
				$("#b6").modal('show');
			});
			var data1 = "{{$data1}}"
			var data2 = "{{$data2}}"
			var titulo = 'OptimusH - NFCe Emitidas no período de ' + data1 + ' até ' + data2
		});

		$(function () {
			$('#example1').DataTable({
				'fixedHeader' : true,
				'lengthChange': true,
				'ordering'    : true,
				'info'        : true,
				'autoWidth'   : true,
				'responsive'  : true,
				'dom': '<l<B>f<t>ip>',
				'buttons': [
					{
						extend: 'pdfHtml5',
						pageSize: 'A4',
						footer: true,
						customize: function(doc) {
							doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
						},
						title: "OptimusH - NFCe Emitidas no período de " + "{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data1)->format('d/m/Y')}}" + " - " + "{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data2)->format('d/m/Y')}}"
					}
		
				]
			})
		})
	</script>
@stop