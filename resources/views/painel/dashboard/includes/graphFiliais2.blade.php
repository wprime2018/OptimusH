<div class="col-md-6">
    <div class="chart-responsive">
        <canvas id="pieChart2" height="200" width="205" style="width: 205px; height: 200px;"></canvas>
    </div>
</div>

<div class="col-md-6">
    <table style="width:100%">
        <tr>
            <td><i class="fa fa-circle" style="color: {{$cores[0]}}"></i> Vendas</td>
            <td>R$ {{number_format($gt['Total'],2,',','.')}}</td>
        </tr>
        <tr>
            <td><i class="fa fa-circle" style="color: {{$cores[1]}}"></i> NFCe</td>
            <td>R$ {{number_format($gt['NFCe'],2,',','.')}}</td>
        </tr>
    </table>        
</div>
