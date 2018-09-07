<div class="box-body">

    <div class="table-responsive">
        <table id="example1{{$num_filial}}" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 100px;">Vendedor</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 80px;">Vendas</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 40px;">Qtde</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 70px;">Ticket Médio</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 70px;">Crédito</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 70px;">Débito</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 70px;">Dinheiro</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 70px;">Comissão</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 60px;">Chip Qtde</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 60px;">Chip Comissão</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 60px;">Total a Pagar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendedores as $nomes => $valores)
                    <tr role="row" class="odd" id="{{$nomes}}">
                    <td>{{$nomes}}</td>
                        <td align="right">{{$valores['Valor']}}</td>
                        <td align="right">{{$valores['Qtde']}}</td>
                        <td align="right">{{$valores['TicketM']}}</td>
                        <td align="right">{{$valores['Cred']}}</td>
                        <td align="right">{{$valores['Deb']}}</td>
                        <td align="right">{{$valores['Din']}}</td>
                        <td align="right">{{$valores['Comissao']}} ({{number_format($valores['Comissao_Paga'],0,',','.')}}%)</td>
                        @if (isset($valores['CHIP']))
                            <td align="right">{{number_format($valores['CHIP']['Quantidade'],0,',','.')}}</td>
                            <td align="right">{{number_format($valores['CHIP']['TotalPagar'],2,',','.')}}</td>
                        @else 
                            <td align="right">0,00</td>
                            <td align="right">0,00</td>
                        @endif
                        <td align="right"><b>{{number_format($valores['TotalPagar'],2,',','.')}}</b></td>
                @endforeach
                    </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">Totais</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>