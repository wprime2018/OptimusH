@extends('adminlte::page') 

@section('title', 'Filiais') 

@section('content_header')

<h1>
	Usuários
	<small>Cadastro</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Usuários</a>
	</li>
	<li>
		<a href="#">Cadastro</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Usuários Cadastrados</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		@include('painel.includes.alerts')
		<a href="{{route('user.create')}}" class="btn btn-primary btn-lg active btn-add">
			<span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
			<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
					 style="width: 100.8px;">Nome</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					 style="width: 150.2px;">Email</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
					 style="width: 350.8px;">Filiais</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
					 style="width: 187.4px;">Cargo</th>
					 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
					 style="width: 187.4px;">Ações</th>

				</tr>

			</thead>
			<tbody>
				@foreach($Users as $user)
				<tr role="row" class="odd" id="{{$user->id}}">
					<td class="sorting_1">{{$user->name}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->filiais}}</td>
					<td>{{$user->cargo}}</td>
					<td>
						<a href="{{ route ( 'user.edit', $user->id ) }}" class="actions edit">
							<span class="btn btn-primary btn-xs glyphicon glyphicon-pencil"></span>
						</a>

						{!! Form::open(['method' => 'DELETE', 'route'=>['user.destroy', $user->id], 'style'=> 'display:inline']) !!}
						{!! Form::submit('Excluir',['class'=> 'btn btn-xs btn-danger']) !!}
						{!! Form::close() !!}
                	</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th rowspan="1" colspan="1">Nome</th>
					<th rowspan="1" colspan="1">Email</th>
					<th rowspan="1" colspan="1">Filiais</th>
					<th rowspan="1" colspan="1">Cargo</th>
					<th rowspan="1" colspan="1">Ações</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@include('painel.modals.modal_confirm')

@stop



@section ('js')
	<script src="{{ asset('js/Painel/config_datatables.js') }}"> </script>
	<script src="{{ asset('js/Painel/modal_confirm.js') }}"></script>
	<!--<script src="{{ asset('js/Painel/BS3DialogMaster.js') }}"></script>-->
@stop