<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 2'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('Ionicons/css/ionicons.min.css') }}">
    <!-- BootStrapp Dialog -->
    <link rel="stylesheet" href="{{ asset('BS3DialogMaster\dist\css\bootstrap-dialog.css') }}">
    <link rel="stylesheet" href="{{ asset('BS3DialogMaster\dist\css\bootstrap-dialog.min.css') }}">

    

    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    
    @if(config('adminlte.plugins.DateRangePicker'))
        <link rel="stylesheet" href="{{asset('plugins/bootstrap-daterangepicker/daterangepicker.css')}}" />
    @endif

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables 
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.css') }}">-->
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/DataTables/datatables.min.css') }}"/>
    @endif

    @yield('adminlte_css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')

<script src="{{ asset('jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>

@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
@endif

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables 
    <script type="text/javascript" src="{{ asset('js\Painel\config_datatables.js') }}"></script>-->
    
    <script type="text/javascript" src="{{ asset('plugins/DataTables/pdfmake-0.1.32/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/DataTables/pdfmake-0.1.32/vfs_fonts.js') }}"></script>

    <script src="{{ asset('plugins/DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/DataTables/DataTables-1.10.16/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{ asset('plugins/DataTables/JSZip-2.5.0/jszip.min.js')}}"></script>
    <script src="{{ asset('plugins/DataTables/Buttons-1.5.1/js/buttons.html5.min.js')}}"></script>
    
@endif

@if(config('adminlte.plugins.DateRangePicker'))
    <!-- DateRangePicker -->
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{ asset('plugins/moment/moment.js')}}"></script>
@endif

@if(config('adminlte.plugins.inputmasks'))
    <!-- InputMasks -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
@endif

@if(config('adminlte.plugins.iCheck'))
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.js') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    
@endif

@if(config('adminlte.plugins.ChartJS'))
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    
@endif


@if(config('adminlte.plugins.BS3Dialogs'))
    <!-- Bs3Dialogs -->
    <script src="{{ asset('BS3DialogMaster\dist\js\bootstrap-dialog.js') }}"></script>
    <script src="{{ asset('BS3DialogMaster\dist\js\bootstrap-dialog.min.js') }}"></script>
@endif

@yield('adminlte_js')

</body>
</html>
