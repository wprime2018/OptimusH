@extends('adminlte::page') 

@section('title', 'Vendas') 

@section('css')
	<link rel="stylesheet" href="{{ asset('css/DataTablesButtons.css') }}">
@stop

@section('content_header')

<h1>
	Vendas
	<small>Diárias</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#table_r_filiais">
			<i class="fa fa-dashboard"></i> Vendas</a>
	</li>
	<li>
		<a href="#table_r_filiais">Resumo diário</a>
	</li>
</ol>
<div class="row">
	<div class="form-group col-md-12">
		<a data-toggle="modal" data-target="b6" id="btnModal6" class="btn btn-primary btn-lg active btn-add">
			<span class="glyphicon glyphicon-filter"></span>Selecionar Período</a>
	</div>
</div>

@stop 

@section('content')

	<input id="mesChanged" name="mesChanged" type="hidden" value="0">
	<input id="anoChanged" name="anoChanged" type="hidden" value="0">

	@if(isset($Filiais))
		@component('painel.boxes.box')
			@slot('boxtitle')
				@if (isset($dados['periodo']))
					<h3 class="box-title">Período: {{$dados['periodo']}}</h3>
				@else 
					<h3 class="box-title">Clique no botão acima e selecione os dados</h3>
				@endif
			@endslot
			@slot('boxbody')
				@include('painel.vendas.rankingDiario01')
			@endslot
			@slot('boxfooter')
			@endslot
		@endcomponent
	@endif
		
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
				@foreach ($minMaxVendas['periodo'] as $meses)
					@if (isset($meses['mes']))
						<option value="{{str_pad($meses['mes'], 2, "0", STR_PAD_LEFT)}}-{{$meses['ano']}}">{{$meses['extenso']}} </option>
					@endif
				@endforeach
			</select>
		</div>
	@endslot
	@slot('btnConfirmar')
		Filtrar
	@endslot
	@endcomponent
@stop

@section('js')

<script type="text/javascript">
	$(document).ready(function(){
		$("#btnModal6").click(function(){
			$("#b6").modal('show');
		});
	});
</script>
	@if(isset($dados2))
		<script type="text/javascript">var dados2 = <?= $dados2 ?>;</script>
		<script type="text/javascript">
			$(document).ready(function(){
				function format ( d ) {
					// `d` is the original data object for the row
					// d.DT_RowId;
					return '<table id="tablechild" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">'+
						'<tr>'+
							'<td>Dinheiro</td>'+
							'<td>'+din+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td>C.Crédito</td>'+
							'<td>'+d.extn+'</td>'+
						'</tr>'+
						'<tr>'+
							'<td>C.Débito</td>'+
							'<td>And any further details here (images etc)...</td>'+
						'</tr>'+
					'</table>';
				}
			
				var table = $('#table_r_filiais').DataTable( {
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
							orientation: 'landscape',
							customize: function(doc) {
								doc.defaultStyle.fontSize = 12; //<-- set fontsize to 16 instead of 10 
								//margin: [left, top, right, bottom]
								doc.pageMargins = [10,10,10,10];
								doc.image = "{{ asset('img/optimush.png') }}";
							}
						}
					]
				} );
				$('#table_r_filiais tbody').on('click', 'td.details-control', function () {
					var tr = $(this).closest('tr');
					var row = table.row( tr );
					var din = "";
					for (var i = 0, length = dados2[d.DT_RowId].length; i < (length - 1); i++) {
						var din = din + '<td>'+dados2[d.DT_RowId][i]['Din']+'</td>'+;
					}    
			 
					if ( row.child.isShown() ) {
						// This row is already open - close it
						row.child.hide();
						tr.removeClass('shown');
					}
					
					else {
						// Open this row
						console.log(row.data().DT_RowId);
						row.child( format(row.data()) ).show();
						tr.addClass('shown');
					}
				} );
			} );
		</script>
	@endif
@stop

