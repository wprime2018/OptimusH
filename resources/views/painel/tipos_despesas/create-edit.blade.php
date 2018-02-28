@extends('adminlte::page') 
@section('title', 'TpDespesas') 

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

@if( isset($errors) && count($errors) > 0 )

	<div class="alert alert-danger">

	@foreach( $errors->all() as $error)
	<p>{{$error}}</p>
	@endforeach 

	</div>

@endif 

@if( isset($TpDespesas) ) 
	{!! Form::model($TpDespesas, ['route' => ['tpDespesa.update', $TpDespesas->id], 'class' => 'form', 'method' => 'put']) !!} 
@else 
	{!! Form::open(['route' => 'tpDespesa.store', 'class' => 'form'])!!} 
@endif

<input type="hidden" name="compartilhada" value="0" />
	
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Cadastro</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!--<form role="form">
					 text input -->
			<div class="row">
				 <div class="form-group col-md-3">
					<label>Descrição</label>
					{!! Form::text('descricao',null,['class' => 'form-control', 'maxlength' => '50', 'id'=>"descricao",'onkeyup'=>'javascript:this.value=this.value.toUpperCase();']) !!}
				 </div>
				 <div class="form-group col-md-2">				
			
					<label></br></br>
						Compartilhada?</label>
					{!! Form::checkbox('compartilhada') !!}
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


@section ('js')
	<script type="text/javascript">
/*		$(function () {
			//iCheck for checkbox and radio inputs
			$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass   : 'iradio_minimal-blue'
			});
			//Red color scheme for iCheck
    		$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      		checkboxClass: 'icheckbox_minimal-red',
      		radioClass   : 'iradio_minimal-red'
    		});
		});
		$(document).ready(function(){
			$('input').iCheck({
				checkboxClass: 'icheckbox_flat',
				radioClass: 'iradio_flat'
			});
		});*/
	</script>
  @stop