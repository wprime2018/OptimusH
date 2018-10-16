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
                <tr role="row" class="odd" id="{{$r}}">
                    <td class="sorting_1">{{$r}}</td>
                    @foreach($valor as $v)
                        <td align="right">R$ {{number_format($v["Total"],2,',','.')}}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>	
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
			$('#example1').DataTable({
				'fixedHeader' : true,
				'lengthChange': true,
				'ordering'    : true,
				'info'        : true,
				'autoWidth'   : true,
				'responsive'  : true,
				'dom': '<l<B>f<t>ip>',
				'buttons': [
					'excelHtml5',
					'csvHtml5',
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4',
						footer: true,
						title: 'OptimusH - Ranking de Vendas com formas de pagamento'
					}
		
				]
			});
		});
    </script>
@endsection