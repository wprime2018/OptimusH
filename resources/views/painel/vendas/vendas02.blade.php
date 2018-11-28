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
                    style="width: 100px;">Qtde Vendas</th>
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
                    @foreach($valor as $v)
                        @if(isset($valor['Din']['Total']))
                            <td align="right"><font color="green" >R$ {{number_format($valor['Total'],2,',','.')}}</td>
                            <td align="right"><font color="green" >R$ {{number_format($valor['Din']['Total'],2,',','.')}}</td>
                            <td align="right"><font color="green" >R$ {{number_format($valor['Cred']['Total'],2,',','.')}}</td>
                            <td align="right"><font color="green" >R$ {{number_format($valor['Deb']['Total'],2,',','.')}}</td>
                            <td align="right"><font color="green" >R$ {{number_format($valor['Qtde_Vendas'],2,',','.')}}</td>
                            <td align="right"><font color="#C71585">R$ {{number_format($valor['TicketM'],2,',','.')}}</td>
                            <td align="right"><font color="green" >R$ 0</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        </tfoot>
    </table>
</div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#btnModal1").click(function(){
                $("#b1").modal('show');
            });
            $("#btnModal2").click(function(){
                $("#b2").modal('show');
            });
            $("#btnModal3").click(function(){
                $("#b3").modal('show');
            });
            $("#btnModal4").click(function(){
                $("#b4").modal('show');
            });
            $("#btnModal5").click(function(){
                $("#b5").modal('show');
            });
            $("#btnModal6").click(function(){
                $("#b6").modal('show');
            });
        });
        $(function () {
            $('#table_r_filiais').DataTable({
                'fixedHeader' : true,
                'lengthChange': true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true,
                'responsive'  : true,
                'dom': '<l<B>f<t>ip>',
                'buttons': [
                    {
                        extend: 'excelHtml5',
                        customize: function( xlsx ) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                        },
                        footer: true,
                        titleAttr: 'Exporta a EXCEL',
                        text: '<i class="fa fa-file-excel-o"></i>',
                    },
                    'csvHtml5',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        footer: true,
                        title: 'OptimusH - Ranking de Vendas resumido por filiais'
                    }
        
                ]
            });
        });
    </script>
@endsection
