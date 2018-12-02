<div class="box-body"
    <p></p>
    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
        <thead>
            <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Forma de PGTO</th>
                @foreach($dados['filiais'] as $f => $valores)	
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 100px;">{{$f}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($dados['recebim'] as $r => $valor)	
                @php
                    ksort($valor);
                @endphp
                @if($valor['*Totais']['Total'] > 0)
                    <tr role="row" class="odd" id="{{$r}}">
                        <td class="sorting_1"><i class="fa fa-circle text-{{($valor['*Totais']['Tipo'] =='C') ? 'blue' : 'green'}}"></i> {{$r}}</td> 
                        @foreach($valor as $v)
                            @if($valor['*Totais']['Tipo'] =='C')
                                <td align="right"><font color="blue">{{number_format($v["Total"],2,',','.')}}</td>
                            @elseif ($valor['*Totais']['Tipo'] =='D')
                                <td align="right"><font color="green">{{number_format($v["Total"],2,',','.')}}</td>
                            @else 
                                <td align="right">{{number_format($v["Total"],2,',','.')}}</td>
                            @endif
                        @endforeach
                    </tr>
                @endif 
            @endforeach
        </tbody>	
    </table>
</div>

