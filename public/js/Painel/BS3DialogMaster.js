$(document).ready(function(){
    $('form').submit(function(e){
      e.preventDefault();
        url = $(this).parent().attr('action');
        var $form=$(this);
        var post=$(this).attr('method')
        BootstrapDialog.confirm({
            title: 'PERIGO!',
            message: 'Ao deletar este registro todos os dados atrelados à ele serão deletados.',
            type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'Voltar!', // <-- Default value is 'Cancel',
            btnOKLabel: 'Deletar!', // <-- Default value is 'OK',
            btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
            callback: function(result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if(result) {
                    $form.submit();
                /*}else {
                    alert('Nope.');*/
                }
            }
        });
    });

});
