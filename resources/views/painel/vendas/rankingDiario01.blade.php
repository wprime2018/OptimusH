<div class="box-body">
    <div class="table-responsive">
        <table id="table_r_filiais" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 100px;">Data</th>
                    @foreach($Filiais as $f)
                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
                            aria-label="Browser: activate to sort column ascending">{{$f->codigo}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($dados as $data => $filial)	
                    @if (is_array($filial))
                        <tr role="row" class="odd" id="{{$data}}">
                            <td class="sorting_1">{{$data}}</td>
                            @foreach($filial as $fCodigo => $valores)
                                @if (isset($dados["$data"]["$fCodigo"]["Total"]))
                                    <td align="right">R$ {{number_format($dados["$data"]["$fCodigo"]["Total"],2,',','.')}}</td>
                                @endif
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">Totais</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $(document).ready(function(){
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
                    extend: 'pdfHtml5',
                    pageSize: 'A4',
                    footer: true,
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 10; //<-- set fontsize to 16 instead of 10 
                        // margin: [left, top, right, bottom]
                        doc.pageMargins = [5,5,5,5];
                        doc.image = "{{ asset('img/optimush.png') }}";
                    },
                    title: "OptimusH - Ranking Diário do período de " + "{{$dados['periodo']}}" 
                }
            ]
        });
    });
</script>
    
@endsection
