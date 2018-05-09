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
		<div class="form-group">
			<label>Período das Vendas</label>

			<div class="input-group">
				<button type="button" class="btn btn-default pull-right" id="daterange-btn">
					<span>March 1, 2018 - March 31, 2018</span>
					<i class="fa fa-caret-down"></i>
				</button>
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
					<th rowspan="1" colspan="1"><font color="green">Dinheiro</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Din"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr >
					<th rowspan="1" colspan="1"><font color="green">Crédito</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Cred"],2,',','.')}}</th>
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
					<th rowspan="1" colspan="1"><font color="#C71585">Ticket Médio</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1"><font color="#C71585">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TicketM"],2,',','.')}}</th>
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
			<table id="table_r_filiais" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
				<thead>
					<tr role="row">
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Filial</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Vendas</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Dinheiro</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Crédito</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Débito</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Qtde Vendas</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Ticket Médio</th>
					</tr>
				</thead>
				<tbody>
					@foreach($Filiais as $f)	
						<tr role="row" class="odd" id="{{$f->id}}">
							<td class="sorting_1">{{$f->codigo}}-{{$f->fantasia}}</td>
							<td><font color="blue">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TotalVendas"],2,',','.')}}</td>
								<td><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Din"],2,',','.')}}</td>
							<td><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Cred"],2,',','.')}}</td>
							<td><font color="#CC9900">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Deb"],2,',','.')}}</td>
							<td><font color="green">{{$formas["$Tr->Recebimento"]["$f->codigo"]["Qtde_Vendas"]}} Vendas</td>
							<td><font color="#C71585">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TicketM"],2,',','.')}}</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<th rowspan="1" colspan="1">Totais</th>
						<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($formas['GranTotalVendas'],2,',','.')}}</th>
						<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($formas['GranTotalDin'],2,',','.')}}</th>
						<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($formas['GranTotalCred'],2,',','.')}}</th>
						<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($formas['GranTotalDeb'],2,',','.')}}</th>
						<th rowspan="1" colspan="1"><font color="blue">{{number_format($formas['GranTotalQtde'],0,',','.')}} Vendas</th>
						<th rowspan="1" colspan="1"><font color="blue">Ticket Médio</th>
					</tr>
				</tfoot>
			</table>
	  </div>
	  <!-- /.table-responsive -->
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

	<script>
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
						title: 'OptimusH - Ranking de Vendas'
					}
		
				]
			}),
		
			$('#daterange-btn').daterangepicker({
					ranges   : {
						'Hoje'       : [moment(), moment()],
						'Ontem'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Últ.Semana' : [moment().subtract(6, 'days'), moment()],
						'Últ.30 Dias': [moment().subtract(29, 'days'), moment()],
						'Este mês'  : [moment().startOf('month'), moment().endOf('month')],
						'Últ.Mês'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					},
					startDate: moment().subtract(29, 'days'),
					endDate  : moment()
				},
				function (start, end) {
					$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
				}
			)
	
			//Date picker
			$('#datepicker').datepicker({
				autoclose: true
			})
	})
	</script>
	<!--<div class="daterangepicker dropdown-menu ltr show-calendar opensleft" style="top: 704px; right: 25.5px; left: auto; display: block;"><div class="calendar left"><div class="daterangepicker_input"><input class="input-mini form-control active" type="text" name="daterangepicker_start" value=""><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time"><div><select class="hourselect"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12" selected="selected">12</option></select> : <select class="minuteselect"><option value="0" selected="selected">00</option><option value="30">30</option></select> <select class="ampmselect"><option value="AM" selected="selected">AM</option><option value="PM">PM</option></select></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"><table class="table-condensed"><thead><tr><th class="prev available"><i class="fa fa-chevron-left glyphicon glyphicon-chevron-left"></i></th><th colspan="5" class="month">Apr 2018</th><th></th></tr><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr><td class="weekend off available" data-title="r0c0">25</td><td class="off available" data-title="r0c1">26</td><td class="off available" data-title="r0c2">27</td><td class="off available" data-title="r0c3">28</td><td class="off available" data-title="r0c4">29</td><td class="off available" data-title="r0c5">30</td><td class="weekend off available" data-title="r0c6">31</td></tr><tr><td class="weekend available" data-title="r1c0">1</td><td class="available" data-title="r1c1">2</td><td class="available" data-title="r1c2">3</td><td class="available" data-title="r1c3">4</td><td class="available" data-title="r1c4">5</td><td class="available" data-title="r1c5">6</td><td class="weekend available" data-title="r1c6">7</td></tr><tr><td class="weekend available" data-title="r2c0">8</td><td class="available" data-title="r2c1">9</td><td class="available" data-title="r2c2">10</td><td class="available" data-title="r2c3">11</td><td class="available" data-title="r2c4">12</td><td class="available" data-title="r2c5">13</td><td class="weekend available" data-title="r2c6">14</td></tr><tr><td class="weekend available" data-title="r3c0">15</td><td class="today active start-date active end-date available" data-title="r3c1">16</td><td class="available" data-title="r3c2">17</td><td class="available" data-title="r3c3">18</td><td class="available" data-title="r3c4">19</td><td class="available" data-title="r3c5">20</td><td class="weekend available" data-title="r3c6">21</td></tr><tr><td class="weekend available" data-title="r4c0">22</td><td class="available" data-title="r4c1">23</td><td class="available" data-title="r4c2">24</td><td class="available" data-title="r4c3">25</td><td class="available" data-title="r4c4">26</td><td class="available" data-title="r4c5">27</td><td class="weekend available" data-title="r4c6">28</td></tr><tr><td class="weekend available" data-title="r5c0">29</td><td class="available" data-title="r5c1">30</td><td class="off available" data-title="r5c2">1</td><td class="off available" data-title="r5c3">2</td><td class="off available" data-title="r5c4">3</td><td class="off available" data-title="r5c5">4</td><td class="weekend off available" data-title="r5c6">5</td></tr></tbody></table></div></div><div class="calendar right"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_end" value=""><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time"><div><select class="hourselect"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11" selected="selected">11</option><option value="12">12</option></select> : <select class="minuteselect"><option value="0">00</option><option value="30">30</option></select> <select class="ampmselect"><option value="AM">AM</option><option value="PM" selected="selected">PM</option></select></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"><table class="table-condensed"><thead><tr><th></th><th colspan="5" class="month">May 2018</th><th class="next available"><i class="fa fa-chevron-right glyphicon glyphicon-chevron-right"></i></th></tr><tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr></thead><tbody><tr><td class="weekend off available" data-title="r0c0">29</td><td class="off available" data-title="r0c1">30</td><td class="available" data-title="r0c2">1</td><td class="available" data-title="r0c3">2</td><td class="available" data-title="r0c4">3</td><td class="available" data-title="r0c5">4</td><td class="weekend available" data-title="r0c6">5</td></tr><tr><td class="weekend available" data-title="r1c0">6</td><td class="available" data-title="r1c1">7</td><td class="available" data-title="r1c2">8</td><td class="available" data-title="r1c3">9</td><td class="available" data-title="r1c4">10</td><td class="available" data-title="r1c5">11</td><td class="weekend available" data-title="r1c6">12</td></tr><tr><td class="weekend available" data-title="r2c0">13</td><td class="available" data-title="r2c1">14</td><td class="available" data-title="r2c2">15</td><td class="available" data-title="r2c3">16</td><td class="available" data-title="r2c4">17</td><td class="available" data-title="r2c5">18</td><td class="weekend available" data-title="r2c6">19</td></tr><tr><td class="weekend available" data-title="r3c0">20</td><td class="available" data-title="r3c1">21</td><td class="available" data-title="r3c2">22</td><td class="available" data-title="r3c3">23</td><td class="available" data-title="r3c4">24</td><td class="available" data-title="r3c5">25</td><td class="weekend available" data-title="r3c6">26</td></tr><tr><td class="weekend available" data-title="r4c0">27</td><td class="available" data-title="r4c1">28</td><td class="available" data-title="r4c2">29</td><td class="available" data-title="r4c3">30</td><td class="available" data-title="r4c4">31</td><td class="off available" data-title="r4c5">1</td><td class="weekend off available" data-title="r4c6">2</td></tr><tr><td class="weekend off available" data-title="r5c0">3</td><td class="off available" data-title="r5c1">4</td><td class="off available" data-title="r5c2">5</td><td class="off available" data-title="r5c3">6</td><td class="off available" data-title="r5c4">7</td><td class="off available" data-title="r5c5">8</td><td class="weekend off available" data-title="r5c6">9</td></tr></tbody></table></div></div><div class="ranges"><div class="range_inputs"><button class="applyBtn btn btn-sm btn-success" type="button">Apply</button> <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button></div></div></div>-->
@stop