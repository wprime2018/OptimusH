@extends('adminlte::page') 

@section('title', 'Produtos') 

@section('content_header')

<h1>
	Produtos
	<small>Importados</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Produtos</a>
	</li>
	<li>
		<a href="#">Importados Ativos</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box">

	<div class="box-header">

		<h3 class="box-title">Produtos Importados (Ativos)</h3>

	</div>

	<div class="box-body">
		
		<div class="form-group col-md-6">

			<a data-toggle="modal" data-target="b1" id="btnModal1" class="btn btn-primary btn-lg active btn-add">
				<span class="glyphicon glyphicon-plus"></span>Importar Produtos</a>

			<a data-toggle="modal" data-target="b2" id="btnModal2" class="btn btn-warning active btn-lg btn-add">
				<span class="glyphicon glyphicon-plus"></span>Calcular Estoque Modal</a>
	
			<a href="{{url('produtos/teste')}}" class="btn btn-warning active btn-lg btn-add">
				<span class="glyphicon glyphicon-plus"></span>Calcular Estoque</a>

			<div class="form-group col-md-6">
				<label>
					<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
					Sintético
				</label>
				<label>
					<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" nochecked="">
					Analítico
				</label>
			</div>
			
		</div>

		<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Browser: activate to sort column ascending"
						style="width: 100px;">Código</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 400px;">Descrição</th>
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column descending"
						style="width: 150.0px;">Fabricante</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 100px;">Preço de Custo</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 100px;">Preço de Venda</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 187.4px;">Data de Cadastro</th>
					@foreach($ListFiliais as $filiais)
						<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="5" aria-label="Engine version: activate to sort column ascending"
						style="width: 45px;">{{$filiais->fantasia}}</br>A M I V</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($Produtos as $produto)
				<tr role="row" class="odd" id="{{$produto->id}}">
					<td class="sorting_1">{{$produto->Codigo}}</td>
					<td>{{$produto->Produto}}</td>
					<td>{{$produto->Fabricante}}</td>
					<td align="right">R$ {{number_format($produto->PrecoCusto, 2, ',', '.')}}</td>
					<td align="right">R$ {{number_format($produto->PrecoVenda, 2, ',', '.')}}</td>
					<td>{{$produto->DataInc}}</td>
					@for ($i = 1; $i <= $totCountFiliais; $i++)
						@foreach($produto->prodEstoque()->where('filial_id',$i)->orderby('filial_id')->get() as $pE)
							@if (count($pE) > 0)
								<td align="right" style="width: 15px;">{{number_format($pE->Atual,0)}}</td>
								<td align="right" style="width: 15px;">{{number_format($pE->Minimo,0)}}</td>
								<td align="right" style="width: 15px;">{{number_format($pE->Ideal,0)}}</td>
								<td align="right" style="width: 15px;">{{number_format($pE->Vendidos,0)}}</td>
								@switch(true)
									@case($pE->Atual > $pE->Ideal)
										<td align="right" style="width: 15px;"><i class="fa fa-circle-o text-green"></i></td>
										@break

									@case($pE->Atual < $pE->Ideal and $pE->Atual > $pE->Minimo)
										<td align="right" style="width: 15px;"><i class="fa fa-circle-o text-yellow"></i></td>
										@break

									@case($pE->Atual < $pE->Minimo )
										<td align="right" style="width: 15px;"><i class="fa fa-circle-o text-red"></i></td>
										@break
									
									@default
										<td align="right" style="width: 15px;">ND</td>
								@endswitch
							@else
								<td align="right" style="width: 15px;">0</td>
								<td align="right" style="width: 15px;">0</td>
								<td align="right" style="width: 15px;">0</td>
								<td align="right" style="width: 15px;">0</td>
							@endif
						@endforeach
					@endfor	
				</tr>
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
					@foreach($ListFiliais as $filiais)
						<th rowspan="1" colspan="3">{{$filiais->fantasia}}</br>A M I</th>
					@endforeach
				</tr>
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
			b1
		@endslot
		@slot('tituloModal')
			Importar Produtos SIC (TabEst1)
		@endslot
		@slot('actionModal')
			Painel\SicTabEst1Controller@importtabest1
		@endslot
		@slot('methodModal')
			post
		@endslot
	
		@slot('bodyModal')
		<div class="form-group col-md-3">
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
			b2
		@endslot
		@slot('tituloModal')
			Calcular Produtos Vendidos
		@endslot
		@slot('actionModal')
			Painel\SicTabEst1Controller@teste
		@endslot
		@slot('methodModal')
			post
		@endslot
	
		@slot('bodyModal')
		<div class="form-group col-md-4">
			<label>Data e hora inicial e final:</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-clock-o"></i>
				</div>
				<input type="text" class="form-control pull-right" id="reservationtime">
			</div>
		</div>

		<script>
			$(function () {
			
				$('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY h:mm A' })
				//Date range as a button
			
			});
		</script>

		<div class="daterangepicker dropdown-menu ltr show-calendar opensleft"><div class="calendar left"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_start" value=""><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time" style="display: none;"><div></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"></div></div><div class="calendar right"><div class="daterangepicker_input"><input class="input-mini form-control" type="text" name="daterangepicker_end" value=""><i class="fa fa-calendar glyphicon glyphicon-calendar"></i><div class="calendar-time" style="display: none;"><div></div><i class="fa fa-clock-o glyphicon glyphicon-time"></i></div></div><div class="calendar-table"></div></div><div class="ranges"><div class="range_inputs"><button class="applyBtn btn btn-sm btn-success" disabled="disabled" type="button">Apply</button> <button class="cancelBtn btn btn-sm btn-default" type="button">Cancel</button></div></div></div>
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
		});
	</script>
@stop