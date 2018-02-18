<div class="modal fade" id="modal-ImpProd" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Importando Produtos do SIC</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-3">
					<label>Filial</label>
					<select name="filial_id" class="form-control">
						@if( isset($Despesas) ) 
							@foreach($ListFiliais as $value)
								<option <?=("{{$value->id}}")? 'selected' : ''?>value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
							@endforeach
						@else 
							<option selected="disabled">Selecionar</option>
							@foreach($ListFiliais as $value)
								<option value="{{$value->id}}">{{$value->codigo}} - {{$value->fantasia}}</option>
							@endforeach
						@endif
					</select>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <input type="file" name="imported-file"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <form action="{{url('produtos/import')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-primary">Importar</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>