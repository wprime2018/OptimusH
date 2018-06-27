<!DOCTYPE HTML>
<!--
	Projection by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
	<html lang="pt-br">
	<head>
		<title>OptimusH</title>
		<meta charset="Content-Type: text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body>

		<!-- Header -->
		<header id="header">
			<div class="inner">
				<a href="#" class="logo"><strong>Um projeto: </strong>WPrime. </a>
				<img src="img/WPrime.png" id="logo" align="center" filter: alpha(opacity=65);>

				<!--<nav id="nav">
				@if (Route::has('login'))
					@auth
						<a href="{{ url('/home') }}">Vamos Come�ar!</a>
					@else
						<a href="{{ route('login') }}">Entrar</a>
						<a href="{{ route('register') }}">Register</a>
					@endauth
				@endif					

				</nav>
				<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>-->
			</div>
		</header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<header>
						<img src="img/optimush.png" name="logo" id="logo" filter: alpha(opacity=65);>
					</header>

					<div class="flex ">

						<div>
							<span class="icon fa-area-chart"></span>
							<h3>Vendas</h3>
							<p>Controle de Vendas</p>
							<p>Por Filiais</p>
							<p>Tudo em uma tela apenas.</p>


						</div>

						<div>
							<span class="icon fa-building-o"></span>
							<h3>Estoque</h3>
							<p>Controle de estoque</p>
							<p>Por Filiais</p>

						</div>

						<div>
							<span class="icon fa-line-chart"></span>
							<h3>Despesas</h3>
							<p>Confronto de Despesas X Faturamento</p>
							<p>Na palma da m�o.</p>

						</div>

					</div>

					<footer>
					@if (Route::has('login'))
						@auth
							<a href="{{ url('/home') }}" class="button">Vamos Come�ar!</a>
						@else
							<a href="{{ route('login') }}" class="button">Entrar</a>
							<!--<a href="{{ route('register') }}">Register</a>-->
						@endauth
					@endif					

					</footer>
				</div>
			</section>


		<!-- Footer -->
			<footer id="footer">
				<div class="inner">

					<div class="copyright">
						&copy; Direitos Autorais. <a href="http://wprime.com.br">WPrime Sistemas</a>.
					</div>

				</div>
			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>