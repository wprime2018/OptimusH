@extends('adminlte::page') 

@section('title', 'Despesas') 

@section('content_header')

<h1>
	Despesas
	<small>Cadastro</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Despesas</a>
	</li>
	<li>
		<a href="#">Cadastro</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Despesas Cadastradas</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<a href="{{url('/despesas/create')}}" class="btn btn-primary btn-lg active btn-add">
			<span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
			<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					 style="width: 150.2px;">Filial</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
					 style="width: 350.8px;">Tipo de Despesa</th>
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
					 style="width: 300.0px;">Descrição</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
					 style="width: 150.8px;">Valor</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
					 style="width: 187.4px;">Data Cadastro</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
					 style="width: 187.4px;">Data do Pagamento</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending"
					 style="width: 135.6px;">Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach($Despesas as $despesa)
				<tr role="row" class="odd" id="{{$despesa->id}}">
					<td class="sorting_1">{{$despesa->fantasia}}</td>
					<td>{{$despesa->desc_tipo}}</td>
					<td>{{$despesa->descricao}}</td>
					<td align="right">{{$despesa->valor}}</td>
					<td>{{$despesa->created_at}}</td>
					<td>{{$despesa->data_pgto}}</td>
					<td>
						<a href="{{ route ( 'despesas.edit', $despesa->id ) }}" class="actions edit">
							<span class="btn btn-primary btn-xs glyphicon glyphicon-pencil"></span>
						</a>

						{!! Form::open(['method' => 'DELETE', 'route'=>['despesas.destroy', $despesa->id], 'style'=> 'display:inline']) !!}
						{!! Form::submit('Excluir',['class'=> 'btn btn-xs btn-danger']) !!}
						{!! Form::close() !!}
                  	</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th rowspan="1" align="right" colspan="4">Valor</th>
					<th rowspan="1" colspan="1">Data Cadastro</th>
					<th rowspan="1" colspan="1">Data Pagamento</th>
					<th rowspan="1" colspan="1">Ações</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@include('painel.modal_confirm')

@stop



@section ('js')
	<script src="{{ asset('js/Painel/config_datatables.js') }}"> </script>
	<script src="{{ asset('js/Painel/modal_confirm.js') }}"></script>
	<!--<script src="{{ asset('js/Painel/BS3DialogMaster.js') }}"></script>-->
@stop