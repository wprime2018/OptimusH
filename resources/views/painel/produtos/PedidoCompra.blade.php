@extends('adminlte::page') 

@section('title', 'Produtos') 

@section('content_header')

<h1>
	Pedido de Compra
	<small>Todos os Produtos do Período Calculado</small>
</h1>
@stop 

@section('content')
<div class="box box-default">	<!-- Table all Products-->

	<div class="box-header with-border">

		<h3 class="box-title">Produtos à serem comprados(Total)</h3>

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
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 50.4px;">Total</th>
					@foreach($filiaisAcomprar as $f)
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 25.4px;">{{$f->filial->codigo}}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($prodDanger as $p)
				@foreach($p->produto()->get(['Codigo','Produto', 'Fabricante', 'PrecoCusto', 'PrecoVenda', 'DataInc']) as $dadosProd)
				<tr role="row" class="odd" id="{{$p->id}}">	
					<td class="sorting_1">{{$dadosProd->Codigo}}</td>
					<td>{{$dadosProd->Produto}}</td>
					<td>{{$dadosProd->Fabricante}}</td>
					<td align="right">R$ {{number_format($dadosProd->PrecoCusto, 2, ',', '.')}}</td>
					<td align="right">R$ {{number_format($dadosProd->PrecoVenda, 2, ',', '.')}}</td>
					<td>{{date_format(new DateTime($dadosProd->DataInc), 'd/m/Y H:i:s')}}</td>
					<td align="center" style="width: 15px;">{{number_format($p->Total_comprar,0)}}</td>
					@foreach($filiaisAcomprar as $f)
						@php
							$prodDangerFilial   = App\Models\Painel\Estoque::where('filial_id',$f->filial_id)
							->where('LkProduto',$p->LkProduto)
							->orderby('LkProduto')
							->first();
						@endphp
						<td align="center" style="width: 15px;">{{number_format($prodDangerFilial->Comprar,0)}}</td>
					@endforeach
				</tr>
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
					<th rowspan="1" colspan="1">Comprar</th>
					@foreach($filiaisAcomprar as $f)
						<th rowspan="1" colspan="1">{{$f->filial->codigo}}</th>
					@endforeach
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@foreach($filiaisAcomprar as $f) <!-- Table for Slaves -->

	<div class="box box-default">

		<div class="box-header with-border">

			<h3 class="box-title">Produtos à serem comprados({{$f->filial->codigo}} - {{$f->filial->fantasia}})</h3>

			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
				</button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>

		</div>

		<div class="box-body">
			
			<table id="example1{{$f->filial->id}}" class="table table-bordered table-striped dataTable cell-border" role="grid" aria-describedby="example1_info">
				<thead>
					<tr role="row">
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Browser: activate to sort column ascending"
							style="width: 100px;">Código</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
							style="width: 400px;">Descrição</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column descending"
							style="width: 150px;">Fabricante</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 100px;">Preço de Venda</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 100px;">Data de Cadastro</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 50.4px;">Comprar</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 50.4px;">Ações</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 50.4px;">Atual</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 50.4px;">Minimo</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 50.4px;">Ideal</th>
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
							style="width: 50.4px;">Vendidos</th>
					</tr>
				</thead>
				<tbody>
					@php
						$prodDangerFilial   = App\Models\Painel\Estoque::where('filial_id',$f->filial_id)
						->where('Comprar','>','0')
						->orderby('LkProduto')
						->with('produto')
						->get();
					@endphp

					@if(count($prodDangerFilial) > 0)
						@foreach($prodDangerFilial as $p)
							<tr role="row" class="odd" id="{{$p->id}}">	
								<td class="sorting_1">{{$p->produto->Codigo}}</td>
								<td>{{$p->produto->Produto}}</td>
								<td>{{$p->produto->Fabricante}}</td>
								<td align="right">R$ {{number_format($p->produto->PrecoVenda, 2, ',', '.')}}</td>
								<td>{{date_format(new DateTime($dadosProd->DataInc), 'd/m/Y H:i:s')}}</td>
								<td align="center" style="width: 15px;">{{number_format($p->Comprar,0)}}</td>
								<td>
									<a data-toggle="modal" data-target="b1" id="btnModal1" class="btn btn-xs btn-danger btnDelete">
										<span class="glyphicon glyphicon-remove"></span>
									</a>
								</td>
								<td align="center" style="width: 15px;">{{number_format($p->Atual,0)}}</td>
								<td align="center" style="width: 15px;">{{number_format($p->Minimo,0)}}</td>
								<td align="center" style="width: 15px;">{{number_format($p->Ideal,0)}}</td>
								<td align="center" style="width: 15px;">{{number_format($p->Vendidos,0)}}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
				<tfoot>
					<tr>
						<th rowspan="1" colspan="1">Código</th>
						<th rowspan="1" colspan="1">Descrição</th>
						<th rowspan="1" colspan="1">Fabricante</th>
						<th rowspan="1" colspan="1">Preço de Venda</th>
						<th rowspan="1" colspan="1">Data de Cadastro</th>
						<th rowspan="1" colspan="1">Comprar</th>
						<th rowspan="1" colspan="1">Ações</th>
						<th rowspan="1" colspan="1">Atual</th>
						<th rowspan="1" colspan="1">Minimo</th>
						<th rowspan="1" colspan="1">Ideal</th>
						<th rowspan="1" colspan="1">Vendidos</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	@if( isset($p) ) 
		@component('painel.modals.modal_danger')
		@slot('txtBtnModal')
			Exclusão de Registros
		@endslot
		@slot('triggerModal')
			b1
		@endslot
		@slot('tituloModal')
			Excluindo Registros ... 
		@endslot
		@slot('routeModal')
			estoques.destroy
		@endslot
		@slot('actionModal')
			{{$p->id}}
		@endslot
		@slot('methodModal')
			DELETE
		@endslot
		@slot('bodyModal')
			<div class='row'>	
				<div class="form-group col-md-12">  <!-- testando tudo -->
					<p>Deseja excluir: {{$p->produto->Codigo}} - {{$p->produto->Produto}}?</p>
				</div>
			</div>
		@endslot
		@slot('btnConfirmar')
			Excluir
		@endslot
		@endcomponent
	@endif
@endforeach


@stop



@section ('js')
	<script src="{{ asset('js/Painel/config_datatables.js') }}"> </script>
	
	<script type="text/javascript">
		$('a.btnDelete').on('click', function (e) {
			e.preventDefault();
			var id = $(this).closest('tr').data('id');
			//aqui passamos a ID do registro para dentro do modal, atraveś do click do botão...
			$('#b1').data('id', id).modal('show');
		});		
	</script>
@stop