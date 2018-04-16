@extends('adminlte::page') 

@section('title', 'Produtos') 

@section('content_header')

<h1>
	Produtos
	<small>Todos os Produtos do Período Calculado</small>
</h1>
@stop 

@section('content')
<div class="box box-default">	<!-- Table all Products-->

	<div class="box-header with-border">

		<h3 class="box-title">Produtos Não Vendidos</h3>

		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>

	</div>

	<div class="box-body">
		
		<table id="example1" class="table table-bordered table-striped dataTable cell-border" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Código</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 400px;">Descrição</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column descending"
						style="width: 150px;">Fabricante</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 100px;">Preço de Custo</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 100px;">Preço de Venda</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 100px;">Data de Cadastro</th>
					@foreach($filiaisAcomprar as $f)
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 25.4px;">{{$f->filial->codigo}}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($prod as $p)
				@foreach($p->produto()->get(['Codigo','Produto', 'Fabricante', 'PrecoCusto', 'PrecoVenda', 'DataInc','Inativo']) as $dadosProd)
				@if($p->Inativo == 0)
				<tr role="row" class="odd" id="{{$p->id}}">	
					<td class="sorting_1">{{$dadosProd->Codigo}}</td>
					<td>{{$dadosProd->Produto}}</td>
					<td>{{$dadosProd->Fabricante}}</td>
					<td align="right">R$ {{number_format($dadosProd->PrecoCusto, 2, ',', '.')}}</td>
					<td align="right">R$ {{number_format($dadosProd->PrecoVenda, 2, ',', '.')}}</td>
					<td>{{date_format(new DateTime($dadosProd->DataInc), 'd/m/Y H:i:s')}}</td>
					@foreach($filiaisAcomprar as $f)
						@php
							$prodFilial   = App\Models\Painel\Estoque::where('filial_id',$f->filial_id)
							->where('LkProduto',$p->LkProduto)
							->where('Atual','>','0')
							->whereNull('Vendidos')
							->orderby('LkProduto')
							->first();
						@endphp
						<td align="center" style="width: 15px;">{{number_format($prodFilial->Atual,0)}}</td>
					@endforeach
				</tr>
				@endif
				@endforeach
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th rowspan="1" colspan="1">Código</th>
					<th rowspan="1" colspan="1">Descrição</th>
					<th rowspan="1" colspan="1">Fabricante</th>
					<th rowspan="1" colspan="1">Preço de Custo</th>
					<th rowspan="1" colspan="1">Preço de Venda</th>
					<th rowspan="1" colspan="1">Data de Cadastro</th>
					@foreach($filiaisAcomprar as $f)
						<th rowspan="1" colspan="1">{{$f->filial->codigo}}</th>
					@endforeach
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@stop

@section ('js')
	<script type="text/javascript"> 
		$(document).ready(function () {
			var table = $('#example1').dataTable();
			var tabletools = new $.fn.dataTable.TableTools(table);
			$(tableTools.fnContainer()).insertBefore('#datatable_wrapper');
		});
			/*$('#example1').DataTable({
				
				"language": {
					"decimal": ",",
					"thousands": "."
				},
				"dom": 'T<"clear">lfrtip',
				"tableTools": {"sSwfPath": "/swf/flashExport.swf"},
				'paging'      : true,
				'fixedHeader' : true,
				'lengthChange': true,
				'searching'   : true,
				'ordering'    : true,
				'info'        : true,
				'autoWidth'   : true,
				'responsive'  : true
			})
		});*/
	</script>
@stop