@extends('adminlte::page') 

@section('title', 'Painel') 

@section('content')

<div class="row">
    <div class="col-md-12">
        @include('painel.dashboard.includes.graphFiliais')        
    </div>
    @stop
    
    @section('js')
        <script type="text/javascript">var param1 = <?= $pieFData1 ?>;</script>
        <script type="text/javascript">var param2 = <?= $pieFData2 ?>;</script>
        <script type="text/javascript">var param3 = <?= $pieFData3 ?>;</script>

        <script type="text/javascript" src ="{{asset('js/Painel/DashBoards/Includes/graphFiliais1.js')}}"></script>
        <script type="text/javascript" src ="{{asset('js/Painel/DashBoards/Includes/graphFiliais2.js')}}"></script>
        <script type="text/javascript" src ="{{asset('js/Painel/DashBoards/Includes/graphFiliais3.js')}}"></script>
    @stop
</div>
