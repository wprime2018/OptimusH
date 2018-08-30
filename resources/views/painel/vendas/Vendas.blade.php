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
							<td align="right">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Total"],2,',','.')}}</td>
						@endforeach
					</tr>
				@endforeach
			</tbody>	
			<tfoot>
				<tr >
					<th rowspan="1" colspan="1"><font color="green">Dinheiro</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1" align="right"><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Din"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr >
					<th rowspan="1" colspan="1"><font color="green">Crédito</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1" align="right"><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Cred"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr>
					<th rowspan="1" colspan="1"><font color="green">Débito</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1" align="right"><font color="green">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Deb"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr>
					<th rowspan="1" colspan="1"><font color="blue">Total de Vendas</th>
					@foreach($Filiais as $f)	
						<th align="right"><font color="blue">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TotalVendas"],2,',','.')}}</th>
					@endforeach
				</tr>
				<tr>
					<th rowspan="1" colspan="1"><font color="#C71585">Ticket Médio</th>
					@foreach($Filiais as $f)	
						<th rowspan="1" colspan="1" align="right"><font color="#C71585">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TicketM"],2,',','.')}}</th>
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
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Total NFCe</th>
					</tr>
				</thead>
				<tbody>
					@foreach($Filiais as $f)	
						<tr role="row" class="odd" id="{{$f->id}}">
							<td class="sorting_1">{{$f->codigo}}-{{$f->fantasia}}</td>
							<td align="right"><font color="green" >R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TotalVendas"],2,',','.')}}</td>
							<td align="right"><font color="green" >R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Din"],2,',','.')}}</td>
							<td align="right"><font color="green" >R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Cred"],2,',','.')}}</td>
							<td align="right"><font color="green" >R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["Deb"],2,',','.')}}</td>
							<td align="right"><font color="green" >{{$formas["$Tr->Recebimento"]["$f->codigo"]["Qtde_Vendas"]}} Vendas</td>
							<td align="right"><font color="#C71585">R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TicketM"],2,',','.')}}</td>
							<td align="right"><font color="green" >R$ {{number_format($formas["$Tr->Recebimento"]["$f->codigo"]["TotalNfce"],2,',','.')}}</td>
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
						<th rowspan="1" colspan="1"><font color="blue">R$ {{number_format($formas['GranTotalNfce'],2,',','.')}}</th>
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

	<script>
		// Tabela das filiais resumidas. 
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
					{
						extend: 'excelHtml5',
						customize: function( xlsx ) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];
							$('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
						},
						footer: true,
						titleAttr: 'Exporta a EXCEL',
						text: '<i class="fa fa-file-excel-o"></i>',
					},
					'csvHtml5',
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4',
						footer: true,
						title: 'OptimusH - Ranking de Vendas resumido por filiais'
					}
		
				]
			})
		}),
		// Tabela das vendas por forma de pagamento 
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
					'excelHtml5',
					'csvHtml5',
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4',
						footer: true,
						title: 'OptimusH - Ranking de Vendas com formas de pagamento'
					}
		
				]
			})
		})
	</script>
								
@stop