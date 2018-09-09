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
                @foreach($dados['formas'] as $nomes => $valores)
                    <tr role="row" class="odd" id="{{$nomes}}">
                    <td>{{$nomes}}</td>
                        <td align="right">{{number_format($valores['Valor'],2,',','')}}</td>
                        <td align="right">{{$valores['Qtde']}}</td>
                        <td align="right">{{number_format($valores['TicketM'],2,',','.')}}</td>
                        <td align="right">{{number_format($valores['Cred'],2,',','')}}</td>
                        <td align="right">{{number_format($valores['Deb'],2,',','')}}</td>
                        <td align="right">{{number_format($valores['Din'],2,',','')}}</td>
                        <td align="right">{{number_format($valores['Comissao'],2,',','')}} ({{number_format($valores['Comissao_Paga'],0,',','.')}}%)</td>
                        @if (isset($valores['CHIP']))
                            <td align="right">{{number_format($valores['CHIP']['Quantidade'],0,',','.')}}</td>
                            <td align="right">{{number_format($valores['CHIP']['TotalPagar'],2,',','.')}}</td>
                        @else 
                            <td align="right">0</td>
                            <td align="right">0,00</td>
                        @endif
                    <td align="right"><b>{{number_format($valores['TotalPagar'],2,',','.')}}</b></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Totais</th>
                    @foreach ($dados['total'] as $item => $totais)

                    @endforeach
                    <th align="right">{{number_format($dados['total']['Valor'],2,',','.')}}</th>
                    <th colspan="2" align="right">{{$dados['total']['Qtde']}}</th>
                    <th align="right">{{number_format($dados['total']['Cred'],2,',','.')}}</th>
                    <th align="right">{{number_format($dados['total']['Deb'],2,',','.')}}</th>
                    <th align="right">{{number_format($dados['total']['Din'],2,',','.')}}</th>
                    <th align="right">{{number_format($dados['total']['Comissao'],2,',','.')}}</th>
                    @if (isset($dados['total']['CHIP']))
                        <th align="right">{{$dados['total']['CHIP']['Qtde']}}</th>
                        <th align="right">{{number_format($dados['total']['CHIP']['Pagar'],2,',','.')}}</th>
                    @else
                        <th align="right">0</th>
                        <th align="right">0,00</th>
                    @endif
                    <th align="right">{{number_format($dados['total']['TotalPagar'],2,',','.')}}</th>
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
