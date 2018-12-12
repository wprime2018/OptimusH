@extends('adminlte::page') 

@section('title', 'Painel') 

@section('content_header')

    <h1>Painel de Controle</h1>
@stop 

@section('content')

<div class="row">
    <div class="col-md-6">
        @include('painel.dashboard.includes.graphFiliaisDet')        
    </div>
    @stop
    
    @section('js')
        <script type="text/javascript">var param1 = <?= $pieFData1 ?>;</script>
        <script type="text/javascript">var param2 = <?= $pieFData2 ?>;</script>

        <script type="text/javascript" src ="{{asset('js/Painel/DashBoards/graphFiliaisDet1.js')}}"></script>
        <script type="text/javascript" src ="{{asset('js/Painel/DashBoards/graphFiliaisDet2.js')}}"></script>

    @stop
</div>
