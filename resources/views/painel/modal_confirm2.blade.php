<!-- ########################### Initialize Modal Window for confirmation ########################-->
<form action="" method="POST" class="remove-record-model">
    <div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="width:55%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="custom-width-modalLabel">Exclusão de Registro</h4>
                </div>
                <div class="modal-body">
                    <h4>Tem certeza de que deseja deletar este registro?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Excluir</button>
                </div>
            </div>
        </div>
    </div>
</form>