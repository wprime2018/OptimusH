@extends('adminlte::page') 

@section('title', 'Tipos de Despesa') 

@section('content_header')

<h1>
	Tipos de Despesa
	<small>Cadastro</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Tipos de Despesa</a>
	</li>
	<li>
		<a href="#">Cadastro</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Tipos de Despesas Cadastrados</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<a href="{{ route ( 'tpDespesa.create' ) }}" class="btn btn-primary btn-lg active btn-add">
			<span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
			<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
					 style="width: 217.8px;">Descrição</th>
					 <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
					 style="width: 217.8px;">Compartilhada</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending"
					 style="width: 135.6px;">Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach($TpDespesas as $tpDespesas)
				<tr role="row" class="odd" id="{{$tpDespesas->id}}">
					<td class="sorting_1">{{$tpDespesas->descricao}}</td>
					<td >{{$tpDespesas->compartilhada}}</td>
					<td>
						<a href="{{ route ( 'tpDespesa.edit', $tpDespesas->id ) }}" class="actions edit">
							<span class="btn btn-primary btn-xs glyphicon glyphicon-pencil"></span>
						</a>

						{!! Form::open(['method' => 'DELETE', 'route'=>['tpDespesa.destroy', $tpDespesas->id], 'style'=> 'display:inline']) !!}
						{!! Form::submit('Excluir',['class'=> 'btn btn-xs btn-danger']) !!}
						{!! Form::close() !!}
                  	</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th rowspan="1" colspan="1">Descrição</th>
					<th rowspan="1" colspan="1">Ações</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@include('Painel.modal_confirm')

@stop



@section ('js')
	<script src="{{ asset('js/Painel/config_datatables.js') }}"> </script>
	<script src="{{ asset('js/Painel/modal_confirm.js') }}"></script>
	<!--<script src="{{ asset('js/Painel/BS3DialogMaster.js') }}"></script>-->
@stop