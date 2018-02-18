@extends('adminlte::page') 
@section('title', 'Usuários') 

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

@if( isset($errors) && count($errors) > 0 )

	<div class="alert alert-danger">

	@foreach( $errors->all() as $error)
	<p>{{$error}}</p>
	@endforeach 

	</div>

@endif 

@if( isset($Users) ) 
	{!! Form::model($Users, ['route' => ['user.update', $Users->id], 'class' => 'form', 'method' => 'put']) !!} 
@else 
	{!! Form::open(['route' => 'user.store', 'class' => 'form'])!!} 
@endif
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Cadastro</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
				<div class="register-box-body">
						<p class="login-box-msg">{{ trans('adminlte::adminlte.register_message') }}</p>
						<form action="{{ url(config('adminlte.register_url', 'register')) }}" method="post">
							{!! csrf_field() !!}
			
							<div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
								<input type="text" name="name" class="form-control" value="{{ old('name') }}"
									placeholder="{{ trans('adminlte::adminlte.full_name') }}">
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
								@if ($errors->has('name'))
									<span class="help-block">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>
							<div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
								<input type="email" name="email" class="form-control" value="{{ old('email') }}"
									placeholder="{{ trans('adminlte::adminlte.email') }}">
								<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>
							<div class="form-group has-feedback {{ $errors->has('cargo') ? 'has-error' : '' }}">
									<input type="cargo" name="cargo" class="form-control" value="{{ old('cargo') }}"
										placeholder="Informe o cargo do usuário">
									<span class="glyphicon glyphicons-family form-control-feedback"></span>
									@if ($errors->has('cargo'))
										<span class="help-block">
											<strong>{{ $errors->first('cargo') }}</strong>
										</span>
									@endif
								</div>
	
							<div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
								<input type="password" name="password" class="form-control"
									placeholder="{{ trans('adminlte::adminlte.password') }}">
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
								@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
							</div>
							<div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
								<input type="password" name="password_confirmation" class="form-control"
									placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
								<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
								@if ($errors->has('password_confirmation'))
									<span class="help-block">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
								@endif
							</div>
							<div class="form-group has-feedback">
								<label>Filiais que o usuário terá acesso:</label>
								<select multiple name="filiais[]" class="form-control">
										@if( isset($Users) ) 
											@foreach($ListFiliais as $value)
												<option <?=("{{$value->id}}")? 'selected' : ''?>value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
											@endforeach
										@else 
											@foreach($ListFiliais as $value)
												<option value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
											@endforeach
										@endif
								</select>
							</div>
							<button type="submit"
									class="btn btn-primary btn-block btn-flat"
							>{{ trans('adminlte::adminlte.register') }}</button>
						</form>
						<!-- /.<div class="auth-links">
							<a href="{{ url(config('adminlte.login_url', 'login')) }}"
							class="text-center">{{ trans('adminlte::adminlte.i_already_have_a_membership') }}</a>
						</div>-->
					</div>
					<!-- /.form-box -->
		</div>
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