<div class="modal fade" id="{{$triggerModal}}" style="display: none;" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['class' =>'form-inline form-delete', 'method'=>"$methodModal", 'action' => "$actionModal", 'enctype'=> 'multipart/form-data' ]) !!} 
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">X</span></button>
                <h4 class="modal-title">{{$tituloModal}}</h4>
            </div>
            <div class="modal-body">
                {{$bodyModal}}
            </div>
            <div class="modal-footer">
                {!! Form::submit("$btnConfirmar",['class' => 'btn btn-success delete', 'name' => 'confirm_modal']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>