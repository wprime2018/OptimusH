<div class="table-responsive">
    <table id="table_r_filiais" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
        <thead>
            <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Filial</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Vendas</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Dinheiro</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Crédito</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Débito</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Clientes</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Ticket Médio</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                    style="width: 100px;">Total NFCe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados['gt'] as $r => $valor)	
                <tr role="row" class="odd" id="{{$r}}">
                    <td class="sorting_1">{{$r}}</td>
                    @if(isset($valor['Total']))
                        <td align="right"><font color="green" >R$ {{number_format($valor['Total'],2,',','.')}}</td>
                        <td align="right"><font color="green" >R$ {{number_format($valor['Din'],2,',','.')}}</td>
                        <td align="right"><font color="green" >R$ {{number_format($valor['Cred'],2,',','.')}}</td>
                        <td align="right"><font color="green" >R$ {{number_format($valor['Deb'],2,',','.')}}</td>
                        <td align="right"><font color="green" >{{number_format($valor['Qtde'],0,',','.')}}</td>
                        <td align="right"><font color="#C71585">R$ {{number_format($valor['TicketM'],2,',','.')}}</td>
                        <td align="right"><font color="green" >R$ {{number_format($valor['NFCe'],2,',','.')}}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                @if(isset($dados['gt3']))
                    <th align="right">R$ {{number_format($dados['gt3']['Total'],2,',','.')}}</th>
                    <th align="right">R$ {{number_format($dados['gt3']['Din'],2,',','.')}}</th>
                    <th align="right">R$ {{number_format($dados['gt3']['Cred'],2,',','.')}}</th>
                    <th align="right">R$ {{number_format($dados['gt3']['Deb'],2,',','.')}}</th>
                    <th align="right">{{number_format($dados['gt3']['Qtde'],0,',','.')}}</th>
                    <th align="right">R$ {{number_format($dados['gt3']['TicketM'],2,',','.')}}</th>
                    <th align="right">R$ {{number_format($dados['gt3']['NFCe'],2,',','.')}}</th>
                @endif
            </tr>
        </tfoot>
    </table>
</div>

