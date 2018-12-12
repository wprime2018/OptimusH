<div class="row">
  <div class='col-md-6'>
    @component('painel.boxes.box')
      @slot('boxtitle')
        Ranking de Filiais
      @endslot
      @slot('boxbody')
        @include('painel.dashboard.includes.graphFiliais1')
      @endslot
      @slot('boxfooter')
      @endslot
    @endcomponent
  </div>

  <div class='col-md-6'>
    @component('painel.boxes.box')
      @slot('boxtitle')
        Vendas X NFCe
      @endslot
      @slot('boxbody')
        @include('painel.dashboard.includes.graphFiliais2')
      @endslot
      @slot('boxfooter')
      @endslot
    @endcomponent
  </div>
</div>
<div class="row">
  <div class="col-md-6">
      @component('painel.boxes.box')
      @slot('boxtitle')
        Vendas X Forma PGTO´s
      @endslot
      @slot('boxbody')
        @include('painel.dashboard.includes.graphFiliais3')
      @endslot
      @slot('boxfooter')
      @endslot
    @endcomponent
  </div>
</div>