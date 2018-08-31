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
		<h3 class="box-title">{{$filial_changed}} - {{$dados[0]['Periodo']}}</h3>
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
						style="width: 150px;">Data</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Chave</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Recibo</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Forma PGTO</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Valor R$</th>
				</tr>
			</thead>
			<tbody>
				@foreach($dados[0]['VendasComNota'] as $V)	
					<tr role="row" class="odd" id="{{$V->nfce->Numero}}">
						<td class="sorting_1">{{$V->nfce->Numero}}</td>
						<td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$V->nfce->Emitida)->format('d/m/Y H:i:s')}}</td>
						<td>{{$V->nfce->Chave}}</td>
						<td>{{$V->nfce->Recibo}}</td>
						<td>{{$V->Receb->Recebimento}}</td>
						<td>{{number_format($V->prodVendidos->sum('Total'),2,',','.')}}</td>
					</tr>
				@endforeach
			</tbody>	
			<tfoot>
				<th>Qtde = {{number_format($dados[0]['QtdeComNF'])}}</th>
				<th>Cartões SNF=</th>
				<th colspan="2">R$ {{number_format($dados[0]['SemNFCred']['Valor'] + $dados[0]['SemNFDeb']['Valor'],2,',','.')}}</th>
				<th>Valor = </th>
				<th>R$ {{number_format($dados[0]['TotalComNF'],2,',','.')}}</th>
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
							doc.pageMargins = [5,5,5,5];
						},
						title: "OptimusH - NFCe Emitidas de " + "{{$filial_changed}}" + " no período de " + "{{$dados[0]['Periodo']}}" 
					}
				]
			})
		})
	</script>
@stop