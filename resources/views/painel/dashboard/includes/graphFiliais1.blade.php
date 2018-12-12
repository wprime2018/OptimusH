<div class="col-md-6">
    <div class="chart-responsive">
        <canvas id="pieChart1" height="200" width="205" style="width: 205px; height: 200px;"></canvas>
    </div>
</div>

<div class="col-md-6">
    <table style="width:100%">
        <tr>
            <th>Filial</th>
            <th>Valor</th>
        </tr>
        @php
        $i = 0;    
        @endphp
        @foreach ($filiais as $f => $valor)
        @if ($i > 11)
            $i = 1;
        @endif
        <tr>
            <td><i class="fa fa-circle" style="color: {{$cores[$i]}}"></i><a href="#"> {{$f}}</a></td>
            <td>R$ {{number_format($valor['Total'],2,',','.')}}</td>
        </tr>
        @php
            ++$i;
        @endphp
        @endforeach
    </table> 
</div>
