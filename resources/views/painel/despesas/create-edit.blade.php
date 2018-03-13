@extends('adminlte::page') 
@section('title', 'Despesas') 

@section('content_header')
<h1>
	Despesas
	<small>{{$title}}</small>
</h1>
<ol class="breadcrumb">
	<li>
		<a href="#">
			<i class="fa fa-dashboard"></i> Despesas</a>
	</li>
	<li>
		<a href="#">{{$title}}</a>
	</li>
</ol>
@stop 

@section('content') 

@if( isset($errors) && count($errors) > 0 )

	<div class="alert alert-danger">

	@foreach( $errors->all() as $error)
	<p>{{$error}}</p>
	@endforeach 

	</div>

@endif 

@if( isset($Despesas) ) 
	@foreach($Despesas as $Desp)
		{!! Form::model($Desp, ['route' => ['despesas.update', $Desp->id], 'class' => 'form', 'method' => 'put']) !!} 
	@endforeach
@else 
	{!! Form::open(['route' => 'despesas.store', 'class' => 'form', 'enctype' => 'multipart/form-data'])!!}
@endif

{{Form::hidden('user_cad', $CurrentUser)}}
<input type="hidden" name="fixa" value="0" />
	
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Cadastro</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!--<form role="form">
				text input -->
			<div class="row">
				<div class="form-group col-md-5">
					<label>Filial</label>
					<select name="filial_id" class="form-control">
						@if( isset($Despesas) ) 
							@foreach($ListFiliais as $value)
								<option <?php echo $Desp->filial_id == "$value->id"?'selected ' : '';?> value="{{$value->id}}">{{$value->fantasia}}</option>
							@endforeach
						@else 
							<option selected="disabled">Selecionar</option>
							@foreach($ListFiliais as $value)
								<option value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
							@endforeach
						@endif
					</select>
				</div>	
				<div class="form-group col-md-7">	<!-- Select Tipos Despesas-->
					<label>Tipo Despesa:</label>
					<select name="tp_desp_id" class="form-control">
						@if( isset($Desp) ) 
							@foreach($ListTpDespesas as $value)
								<option {{($Desp->tp_desp_id == "$value->id")? 'selected ' : ''}}value="{{$value->id}}">{{$value->descricao}}</option>
							@endforeach
						@else 
							<option selected="disabled">Selecionar</option>
							@foreach($ListTpDespesas as $value2)
								<option value="{{$value2->id}}">{{$value2->descricao}}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-4">
					<label>Descrição</label>
					{!! Form::text('descricao',null,['class'=> 'form-control' , 'maxlength' => '30']) !!}
				</div>
				<div class="form-group col-md-2">
					<label>Documento</label>
					{{ Form::number('documento',null,['maxlength' => '10', 'class' => 'form-control']) }}
				</div>
				<div class="form-group col-md-2">
					<label>Tipo PGTO:</label>
					<select name="tp_pgto" class="form-control">
						<option selected="Enabled" value="Dinheiro">Dinheiro</option>
						<option value="Banco">Banco</option>
						<option value="Cheque">Cheque</option>
					</select>
				</div>
			
				<div class="form-group col-md-2">
					<label>Valor</label>
					{{ Form::number('valor',null,['maxlength' => '10', 'step'=>'0.01', 'placeholder'=>'0.00', 'class' => 'form-control']) }}
				</div>
			
				<div class="form-group col-md-2">
					<label>QTDE de Parcelas:</label>
					{{ Form::number('qtde_parcelas',null,['maxlength' => '2', 'class' => 'form-control']) }}
					<label>Despesa Fixa?</label>
					{{ Form::checkbox('fixa') }}
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-6">
					<label>Data Pagamento:</label>
					@if( isset($Desp) ) 
						<input name="data_pgto" type="datetime-local" id="data_pgto" class="form-control" value="{{$Desp->data_pgto}}">
					@else 
						<input name="data_pgto" type="datetime-local" id="data_pgto" class="form-control">
					@endif
					<label>Comprovante escaneado:</label>
					<input type="file" name="image">
					@if( isset($Desp) ) 
						<img src="{{ url("despesas/{$Desp->path_comp}") }}" alt="{{ $Desp->descricao }}">
					@endif
				</div>
				<div class="form-group col-md-6">
					<label>Obs:</label>
					{{ Form::textarea('notes', null, ['class' => 'form-control']) }}
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button type="submit" class="btn btn-primary" accesskey="G">Gravar</button>
		<button type="reset" class="btn btn-warning" accesskey="R">Redefinir</button>
	</div>
</form>
@stop 
