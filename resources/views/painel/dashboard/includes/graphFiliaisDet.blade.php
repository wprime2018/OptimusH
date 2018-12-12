<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Ranking de filiais</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div>  <!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-md-8">
        <div class="chart-responsive">
          <canvas id="pieChart" height="200" width="205" style="width: 205px; height: 200px;"></canvas>
        </div>
        <!-- ./chart-responsive -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <ul class="chart-legend clearfix">
          @php
            $i = 0;    
          @endphp
          @foreach ($filiais as $f => $valor)
            @php
              ++$i;
            @endphp
            @if ($i > 11)
              $i = 1;
            @endif
            <li><i class="fa fa-circle" style="color: {{$cores[$i]}}"></i> <a href="#">{{$f}} - R$ {{number_format($valor['Total'],2,',','.')}}</a></li>
          @endforeach
        </ul>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.box-body -->
  <div class="box-footer no-padding">
    <ul class="nav nav-pills nav-stacked">
      @php
      $i = 0;    
    @endphp
    @foreach ($filiais as $f => $valor)
      @php
        ++$i;
      @endphp
      @if ($i > 11)
        $i = 1;
      @endif
      <li>
      </li>

      @endforeach
    </ul>
  </div>
  <!-- /.footer -->
</div>
