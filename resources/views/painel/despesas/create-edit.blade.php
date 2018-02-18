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

@if( isset($errors) && count($errors) > 0 )

	<div class="alert alert-danger">

	@foreach( $errors->all() as $error)
	<p>{{$error}}</p>
	@endforeach 

	</div>

@endif 

@if( isset($Despesas) ) 
	{!! Form::model($Despesas, ['route' => ['despesas.update', $Despesas->id], 'class' => 'form', 'method' => 'put']) !!} 
@else 
	{!! Form::open(['route' => 'despesas.store', 'class' => 'form'])!!}
	{!! Form::hidden('user_cad', '$CurrentUser') !!}
@endif

<input type="hidden" name="fixa" value="0" />
	
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Cadastro</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!--<form role="form">
				 text input -->
			<div class="col-md-8">		<!-- Select Filiais-->
				<div class="form-group col-md-5">
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
				<div class="form-group col-md-7">	<!-- Select Filiais-->
					<label>Tipo Despesa:</label>
					<select name="tp_desp_id" class="form-control">
						@if( isset($Despesas) ) 
							@foreach($ListTpDespesas as $value)
								<option <?=($tp_desp == "{{$value->id}}")? 'selected' : ''?>value="{{$value->id}}">{{$value->descricao}} - {{$value->fantasia}}</option>
							@endforeach
						@else 
							<option selected="disabled">Selecionar</option>
							@foreach($ListTpDespesas as $value2)
								<option value="{{$value2->id}}">{{$value2->descricao}}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="form-group col-md-5">
					<label>Descrição</label>
					{!! Form::text('descricao',null,['class'=> 'form-control' , 'maxlength' => '30']) !!}
				</div>
				<div class="form-group col-md-3">
					<label>Documento</label>
					<label>{{ Form::number('documento',null,['class' => 'form-control', 'max' => '9999999999']) }}</label>
				</div>
				<div class="form-group col-md-2">
					<label>Tipo PGTO:</label>
					<select name="tp_pgto" class="form-control">
						<option selected="Enabled" value="Dinheiro">Dinheiro</option>
						<option value="Banco">Banco</option>
						<option value="Cheque">Cheque</option>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label>Valor</label>
					<input name="valor" type="number" step="0.01" id="valor_pgto" maxlength="11" class="form-control">
				</div>
				<div class="form-group col-md-3">
					<label>QTDE de Parcelas:</label>
					{{ Form::number('qtde_parcelas',null,['maxlength' => '2', 'class' => 'form-control']) }}
					<label>Despesa Fixa?</label>
					{{ Form::checkbox('fixa') }}
				</div>
				<div class="form-group col-md-4">
					<label>Data Pagamento:</label>
					<input name="data_pgto" type="datetime-local" id="data_pgto" class="form-control">
				</div>
				<div class="form-group col-md-9">
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
