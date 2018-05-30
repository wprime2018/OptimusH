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
							<div class="timeline-item">
								<span class="time"><i class="fa fa-clock-o"></i>Escolha seu avatar</span>
				
								<h3 class="timeline-header"><a href="#">Imagem de Perfil </a> uploaded new photos</h3>
				
								<div class="timeline-body">
									<img src="../img/avatar.png" width="80px" height="80px" alt="..." class="margin">
									<img src="../img/avatar2.png" width="80px" height="80px" alt="..." class="margin">
									<img src="../img/avatar3.png" width="80px" height="80px" alt="..." class="margin">
									<img src="../img/avatar4.png" width="80px" height="80px" alt="..." class="margin">
									<img src="../img/avatar5.png" width="80px" height="80px" alt="..." class="margin">
								</div>
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
