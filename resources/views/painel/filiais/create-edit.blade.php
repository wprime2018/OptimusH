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

@if( isset($errors) && count($errors) > 0 )

	<div class="alert alert-danger">

	@foreach( $errors->all() as $error)
	<p>{{$error}}</p>
	@endforeach 

	</div>

@endif 

@if( isset($Filiais) ) 
	{!! Form::model($Filiais, ['route' => ['filial.update', $Filiais->id], 'class' => 'form', 'method' => 'put']) !!} 
@else 
	{!! Form::open(['route' => 'filial.store', 'class' => 'form'])!!} 
@endif

<input type="hidden" name="ativo" value="0" />
	
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Cadastro</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!--<form role="form">
				 text input -->
			<div class="col-md-6">
				<div class="form-group col-md-2">
					<label>Código Interno</label>
					{!! Form::text('codigo',null,['class' => 'form-control', 'maxlength' => '3', 'id'=>"codigo",'onkeyup'=>'javascript:this.value=this.value.toUpperCase();']) !!}
				</div>
				<div class="form-group col-md-3">
					<label>Fantasia</label>
					{!! Form::text('fantasia',null,['class' => 'form-control', 'maxlength' => '30']) !!}
				</div>
				<div class="form-group col-md-7">
					<label>Razão Social</label>
					{!! Form::text('razao_social',null,['class' => 'form-control', 'maxlength' => '60']) !!}
				</div>
				<div class="form-group col-md-2">
					<label>C.E.P.</label>
					<!--input type="text" class="form-control" id = "cep" maxlength="9" data-inputmask="mask": "99999-999" data-mask="">-->
					{!! Form::number('cep',null,['class' => 'form-control', 'max' => '99999999', 'id'=>"cep"]) !!}
				</div>
				<div class="form-group col-md-8">
					<label>Logradouro</label>
					{!! Form::text('logradouro',null,['class' => 'form-control', 'maxlength' => '40', 'id'=>'logr']) !!}
				</div>
				<div class="form-group col-md-2">
					<label>Número</label>
					{!! Form::number('numero',null,['class' => 'form-control', 'maxlength' => '6', 'id'=>"num_logr"]) !!}
				</div>
				<div class="form-group col-md-2">
					<label>Complemento</label>
					{!! Form::text('compl',null,['class' => 'form-control', 'maxlength' => '30', 'id'=>'comp_logr']) !!}
				</div>
				<div class="form-group col-md-4">
					<label>Bairro</label>
					{!! Form::text('bairro',null,['class' => 'form-control', 'maxlength' => '30', 'id'=>'bairro_logr']) !!}
				</div>
				<div class="form-group col-md-4">
					<label>Cidade</label>
					{!! Form::text('cidade',null,['class' => 'form-control', 'maxlength' => '30', 'id'=>'cidade_logr']) !!}
				</div>
				<div class="form-group col-md-2">
					<label>Estado</label>
					{!! Form::text('estado',null,['class' => 'form-control', 'maxlength' => '2', 'id'=>'estado_logr']) !!}
				</div>
				<div class="form-group col-md-2">
					<label>IBGE</label>
					{!! Form::number('ibge',null,['class' => 'form-control', 'maxlength' => '7', 'id'=>"ibge"]) !!}
					<label>Ativo?</label>
					{!! Form::checkbox('ativo') !!}
				</div>
				<div class="form-group col-md-4">
					<label>C.N.P.J.</label>
					<!--<input type="text" class="form-control" id = "cnpj" maxlength="18" data-inputmask="mask": "99.999.999/9999-99" data-mask="">-->
					{!! Form::number('cnpj',null,['class' => 'form-control', 'maxlength' => '14', 'id'=>"cnpj"]) !!}
					<!--<input type="text" name="cnpj" id="cnpj" onkeyup="FormataCnpj(this,event)" onblur="if(!validarCNPJ(this.value)){alert('CNPJ Informado é inválido'); this.value='';}" maxlength="18"  class="form-control input-md" ng-model="cadastro.cnpj" required>-->
				</div>
				<div class="form-group col-md-3">
					<label>Inscrição Estadual</label>
					{!! Form::number('ie',null,['class' => 'form-control', 'maxlength' => '9', 'id'=>"ie"]) !!}
				</div>
				<div class="form-group col-md-3">
					<label>Inscrição Municipal</label>
					{!! Form::number('im',null,['class' => 'form-control', 'maxlength' => '15', 'id'=>"im"]) !!}
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
		$(document).ready(function(){
		//$("#cep").inputmask("99999-999");
		//$("#cnpj").inputmask("99.999.999/9999-99");
		//});
		function alteraMaiusculo(){
			var valor = document.getElementById("codigo").texto;
			var novoTexto = valor.value.toUpperCase();
			valor.value = novoTexto;
		}});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {

			function limpa_formulário_cep() {
				// Limpa valores do formulário de cep.
				$("#logr").val("");
				$("#bairro_logr").val("");
				$("#cidade_logr").val("");
				$("#estado_logr").val("");
				$("#ibge").val("");
				
			}
			
			//Quando o campo cep perde o foco.
			$("#cep").blur(function() {

				//Nova variável "cep" somente com dígitos.
				var cep = $(this).val().replace(/\D/g, '');

				//Verifica se campo cep possui valor informado.
				if (cep != "") {

					//Expressão regular para validar o CEP.
					var validacep = /^[0-9]{8}$/;

					//Valida o formato do CEP.
					if(validacep.test(cep)) {

						//Preenche os campos com "..." enquanto consulta webservice.
						$("#logr").val("...");
						$("#bairro_logr").val("...");
						$("#cidade_logr").val("...");
						$("#estado_logr").val("...");
						$("#ibge").val("...");
						

						//Consulta o webservice viacep.com.br/
						$.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

							if (!("erro" in dados)) {
								//Atualiza os campos com os valores da consulta.
								$("#logr").val(dados.logradouro);
								$("#bairro_logr").val(dados.bairro);
								$("#cidade_logr").val(dados.localidade);
								$("#estado_logr").val(dados.uf);
								$("#ibge").val(dados.ibge);
								
							} //end if.
							else {
								//CEP pesquisado não foi encontrado.
								limpa_formulário_cep();
								alert("CEP não encontrado.");
							}
						});
					} //end if.
					else {
						//cep é inválido.
						limpa_formulário_cep();
						alert("Formato de CEP inválido.");
					}
				} //end if.
				else {
					//cep sem valor, limpa formulário.
					limpa_formulário_cep();
				}
			});
		});
	</script>
@stop