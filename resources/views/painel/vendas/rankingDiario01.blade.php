<div class="box-body">
    <div class="table-responsive">
        <table id="table_r_filiais" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
                <tr role="row">
                    <th class="details-control sorting_disabled" rowspan="1" colspan="1" style="width: 5px;" aria-label=""></th>
                    <th class="details-control sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending"
                        style="width: 100px;">Data</th>
                    @foreach($Filiais as $f)
                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
                            aria-label="Browser: activate to sort column ascending">{{$f->codigo}}</th>
                    @endforeach
                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
                            aria-label="Browser: activate to sort column ascending">Total</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($dados as $d => $data)	
                    @if (is_array($data))
                        <tr role="row" class="odd" id="{{$d}}">
                            <td class="details-control"></td>
                            <td class="sorting_1">{{$data[0]}}</td>

                            @php
                            $i = 1;    
                            @endphp
                            @for ($i = 1; $i <= (count($data) -1) ; $i++)
                                <td align="right">{{number_format($data[$i]['Total'],2,',','')}}</td>    
                            @endfor
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