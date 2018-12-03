<div class="box-body"
	<p></p>
	<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
		<thead>
			<tr role="row">
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					style="width: 100px;">Número</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					style="width: 150px;">Data</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					style="width: 100px;">Chave</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					style="width: 100px;">Recibo</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					style="width: 100px;">Forma PGTO</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
					style="width: 100px;">Valor R$</th>
			</tr>
		</thead>
		<tbody>
			@for ($i = 0; $i < $dados['QtdeComNF']; $i++)
				<tr role="row" class="odd" id="{{$dados[$i]['Numero']}}">
					<td class="sorting_1">{{$dados[$i]['Numero']}}</td>
					<td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$dados[$i]['Data'])->format('d/m/Y H:i:s')}}</td>
					<td>{{$dados[$i]['Chave']}}</td>
					<td>{{$dados[$i]['Recibo']}}</td>
					<td>{{$dados[$i]['Receb']}}</td>
					<td>{{number_format($dados[$i]['Valor'],2,',','.')}}</td>
				</tr>
			@endfor
		</tbody>	
		<tfoot>
			<th colspan="4">Qtde = {{$dados['QtdeComNF']}}</th>
			<th>Valor = </th>
			<th>R$ {{number_format($dados['TotalComNF'],2,',','.')}}</th>
			<tr>
				<td>Não encontradas</td>
				<td colspan="7">{{$dados['NoFind']}}</td>
			</tr>
		</tfoot>
	</table>
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
						customize: function(doc) {
							doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10 
							// margin: [left, top, right, bottom]
							doc.pageMargins = [5,5,5,5];
							doc.image = "{{ asset('img/optimush.png') }}";
						},
						title: "OptimusH - NFCe Emitidas de " + "{{$dados['filial_changed']}}" + " no período de " + "{{$dados['Periodo']}}" 
					}
				]
			});
		});
	</script>
@stop
