@extends('adminlte::page') 

@section('title', 'Filiais') 

@section('content_header')

<h1>
	Filiais
	<small>Cadastro</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Filiais</a>
	</li>
	<li>
		<a href="#">Cadastro</a>
	</li>
</ol>
@stop 

@section('content')
<div class="box">
	<div class="box-header">
		<h3 class="box-title">Filiais Cadastradas</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<a href="{{url('/filial/create')}}" class="btn btn-primary btn-lg active btn-add">
			<span class="glyphicon glyphicon-plus"></span> Cadastrar</a>
			<p></p>
		<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending"
						style="width: 100.8px;">Código</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
						style="width: 150.2px;">Fantasia</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending"
						style="width: 350.8px;">Razão Social</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"
						style="width: 187.4px;">C.N.P.J.</th>
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending"
						style="width: 135.6px;">Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach($Filiais as $filial)
				<tr role="row" class="odd" id="{{$filial->id}}">
					<td class="sorting_1">{{$filial->codigo}}</td>
					<td>{{$filial->fantasia}}</td>
					<td>{{$filial->razao_social}}</td>
					<td>{{$filial->cnpj}}</td>
					<td>
						<a href="{{ route ( 'filial.edit', $filial->id ) }}" class="actions edit">
							<span class="btn btn-primary btn-xs glyphicon glyphicon-pencil"></span>
						</a>

						<a data-toggle="modal" data-target="b1" id="btnModal1" class="btn btn-xs btn-danger btnDelete">
							<span class="glyphicon glyphicon-remove"></span></a>

					<!--{!! Form::open(['method' => 'DELETE', 'route'=>['filial.destroy', $filial->id], 'style'=> 'display:inline']) !!}
						{!! Form::submit('Excluir',['class'=> 'btn btn-xs btn-danger']) !!}
						{!! Form::close() !!}-->
                	</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th rowspan="1" colspan="1">Código</th>
					<th rowspan="1" colspan="1">Fantasia</th>
					<th rowspan="1" colspan="1">Razão Social</th>
					<th rowspan="1" colspan="1">CNPJ</th>
					<th rowspan="1" colspan="1">Ações</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@if( isset($filial) ) 
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
		filial.destroy
	@endslot
	@slot('actionModal')
		$filial->id
	@endslot
	@slot('methodModal')
		DELETE
	@endslot
	@slot('bodyModal')
	<div class='row'>	
		<div class="form-group col-md-12">  <!-- testando tudo -->
			<p>Deseja excluir a filial: {{$filial->codigo}} - {{$filial->fantasia}}?</p>
		</div>

	@endslot
	@slot('btnConfirmar')
		Excluir
	@endslot
	@endcomponent
@endif 

@stop



@section ('js')
	<script src="{{ asset('js/painel/config_datatables.js') }}"> </script>
	<script type="text/javascript">
		$('a.btnDelete').on('click', function (e) {
			e.preventDefault();
			var id = $(this).closest('tr').data('id');
			//aqui passamos a ID do registro para dentro do modal, atraveś do click do botão...
			$('#b1').data('id', id).modal('show');
		});
	</script>
@stop