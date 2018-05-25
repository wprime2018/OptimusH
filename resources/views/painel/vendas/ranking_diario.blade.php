@extends('adminlte::page') 

@section('title', 'Vendas') 

@section('content_header')

<h1>
	Vendas
	<small>Diárias</small>
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
	<div class="box-header with-border">
		<div class="form-group col-md-12">
			<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
		</div>

		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
 	<!-- /.box-header -->
	<div class="box-body">
		
		<div class="table-responsive">
			<table id="table_r_filiais" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
				<thead>
					<tr role="row">
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Data</th>
					@foreach($Filiais as $f)	
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
						aria-label="Browser: activate to sort column ascending">{{$f->codigo}}</th>
					@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach($formas as $data => $filial)	
						<tr role="row" class="odd" id="{{$data}}">
							<td class="sorting_1">{{$data}}</td>
							@foreach($filial as $fCodigo => $valores)
								<td align="Right">R$ {{number_format($formas["$data"]["$fCodigo"]["Total"],2,',','.')}}</td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
				@foreach($formas as $data => $filial)	
				<tr role="row" class="odd" id="{{$data}}">
					<td class="sorting_1">{{$data}}</td>
					@foreach($filial as $fCodigo => $valores)
						<td align="Right">R$ {{number_format($formas["$data"]["$fCodigo"]["Total"],2,',','.')}}</td>
					@endforeach
				</tr>
			@endforeach
				<tfoot>
					<tr>
						<th rowspan="1" colspan="1">Totais</th>
						@foreach($soma as $s => $value)
							<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($value['Total'],2,',','.')}}</th>
						@endforeach
					</tr>
				</tfoot>
			</table>
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
	Painel\Vendas@ranking_diario
@endslot
@slot('methodModal')
	get
@endslot

@slot('bodyModal')
<div class="form-group col-md-4">
	<label>Selecione o mês</label>
	<select class="form-control" name="month_date">
		<option value="1" @if(Carbon\Carbon::now()->month == 1) ? selected @endif>Janeiro</option>
		<option value="2" @if(Carbon\Carbon::now()->month == 2) ? selected @endif>Feveiro</option>
		<option value="3" @if(Carbon\Carbon::now()->month == 3) ? selected @endif>Março</option>
		<option value="4" @if(Carbon\Carbon::now()->month == 4) ? selected @endif>Abril</option>
		<option value="5" @if(Carbon\Carbon::now()->month == 5) ? selected @endif>Maio</option>
		<option value="6" @if(Carbon\Carbon::now()->month == 6) ? selected @endif>Junho</option>
		<option value="7" @if(Carbon\Carbon::now()->month == 7) ? selected @endif>Julho</option>
		<option value="8" @if(Carbon\Carbon::now()->month == 8) ? selected @endif>Agosto</option>
		<option value="9" @if(Carbon\Carbon::now()->month == 9) ? selected @endif>Setembro</option>
		<option value="10" @if(Carbon\Carbon::now()->month == 10) ? selected @endif>Outubro</option>
		<option value="11" @if(Carbon\Carbon::now()->month == 11) ? selected @endif>Novembro</option>
		<option value="12" @if(Carbon\Carbon::now()->month == 12) ? selected @endif>Dezembro</option>
	  </select>
</div>
<div class="form-group col-md-4">
	<label>Selecione o ano</label>
	<select class="form-control" name="year_date">
			<option value="2018" @if(Carbon\Carbon::now()->year == 2018) ? selected @endif>2018</option>
			<option value="2017" @if(Carbon\Carbon::now()->year == 2017) ? selected @endif>2017</option>
			<option value="2016" @if(Carbon\Carbon::now()->year == 2016) ? selected @endif>2016</option>
			<option value="2015" @if(Carbon\Carbon::now()->year == 2015) ? selected @endif>2015</option>
			<option value="2014" @if(Carbon\Carbon::now()->year == 2014) ? selected @endif>2014</option>
			<option value="2013" @if(Carbon\Carbon::now()->year == 2013) ? selected @endif>2013</option>
			<option value="2012" @if(Carbon\Carbon::now()->year == 2012) ? selected @endif>2012</option>
			<option value="2011" @if(Carbon\Carbon::now()->year == 2011) ? selected @endif>2011</option>
			<option value="2010" @if(Carbon\Carbon::now()->year == 2010) ? selected @endif>2010</option>
		  </select>
	</div>
@endslot
@slot('btnConfirmar')
	Filtrar
@endslot
@endcomponent
@stop

@section ('js')

<script>
	$(document).ready(function(){
		$("#btnModal6").click(function(){
			$("#b6").modal('show');
		});
	});
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
					footer: true
				}
	
			]
		})
	})
</script>

@stop