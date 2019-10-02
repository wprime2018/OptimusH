<div class="box-body">

    <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 100px;">Vendedor</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 80px;">Vendas</th>
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
                        style="width: 60px;">Ticket Médio</th>
                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 60px;">Total a Pagar</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($dados['dados']))
                @for ($i = 0; $i < count($dados['dados']); $i++)
                    <tr role="row" class="odd" id="{{$i}}">
                        <td>{{$dados['dados'][$i]['Vendedor']}}</td>
                        <td align="right">{{number_format($dados['dados'][$i]['Valor'],2,',','')}}</td>
                        <td align="right">{{number_format($dados['dados'][$i]['Cred'],2,',','')}}</td>
                        <td align="right">{{number_format($dados['dados'][$i]['Deb'],2,',','')}}</td>
                        <td align="right">{{number_format($dados['dados'][$i]['Din'],2,',','')}}</td>
                        <td align="right">{{number_format($dados['dados'][$i]['Comissao'],2,',','')}}</td>
                        @if (isset($dados['dados'][$i]['ChipTotal']))
                            <td align="right">{{number_format($dados['dados'][$i]['ChipQtde'],0,',','.')}}</td>
                            <td align="right">{{number_format($dados['dados'][$i]['ChipTotal'],2,',','.')}}</td>
                        @else 
                            <td align="right">0</td>
                            <td align="right">0,00</td>
                        @endif
                        <td align="right"><b>{{ number_format($dados['dados'][$i]['TicketMedio'],2,',','.') }}</b></td>
                        <td align="right"><b>{{number_format($dados['dados'][$i]['TotalPagar'],2,',','.')}}</b></td>
                    </tr>
                @endfor
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th>Totais</th>
                    @if (isset($dados['gt']))
                        <th align="right">{{number_format($dados['gt'][0]['Valor'],2,',','.')}}</th>
                        <th align="right">{{number_format($dados['gt'][0]['Cred'],2,',','.')}}</th>
                        <th align="right">{{number_format($dados['gt'][0]['Deb'],2,',','.')}}</th>
                        <th align="right">{{number_format($dados['gt'][0]['Din'],2,',','.')}}</th>
                        <th align="right">{{number_format($dados['gt'][0]['Comissao'],2,',','.')}}</th>
                        <th align="right">{{$dados['gt'][0]['ChipQtde']}}</th>
                        <th align="right">{{number_format($dados['gt'][0]['ChipTotal'],2,',','.')}}</th>
                        <th align="right">-</th>
                        <th align="right">{{number_format($dados['gt'][0]['TotalPagar'],2,',','.')}}</th>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@section ('js')
	<script type="text/javascript">
		$(document).ready(function(){
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
					{
						extend: 'pdfHtml5',
						pageSize: 'A4',
                        footer: true,
                        orientation: 'landscape',
						customize: function(doc) {
							doc.defaultStyle.fontSize = 10; //<-- set fontsize to 16 instead of 10 
							// margin: [left, top, right, bottom]
							doc.pageMargins = [10,10,10,10];
							doc.image = "{{ asset('img/optimush.png') }}";
						},
						title: "OptimusH - Ranking Vendedores " + "{{$filial_changed}}" + " no período de " + "{{$periodo}}" 
					}
				]
			});
		});
	</script>
@stop
