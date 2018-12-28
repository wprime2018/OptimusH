$(document).ready(function(){
    var totalFiliais = count(dados2);
    function format ( d, id ) {
        // `d` is the original data object for the row
        var i = 0;
        var din,cred,deb;

        for (i = 0; i < (totalFiliais -1); i++) {
            din += '<td>'+dados2[id][i]['Din']+'</td>';
        } 
        for (i = 0; i < (totalFiliais -1); i++) {
            cred += '<td>'+dados2[id][i]['Cred']+'</td>';
        } 
        for (i = 0; i < (totalFiliais -1); i++) {
            deb += '<td>'+dados2[id][i]['Deb']+'</td>';
        } 

        return '<table class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">'+
            '<tr>'+
                '<td>Dinheiro</td>'+
                din+
            '</tr>'+
            '<tr>'+
                '<td>C.Crédito</td>'+
                cred+
            '</tr>'+
            '<tr>'+
                '<td>C.Débito</td>'+
                deb+
            '</tr>'+
        '</table>';
    }
    var table = $('#table_r_filiais').DataTable( {
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
                    doc.defaultStyle.fontSize = 12; //<-- set fontsize to 16 instead of 10 
                    //margin: [left, top, right, bottom]
                    doc.pageMargins = [10,10,10,10];
                    doc.image = "{{ asset('img/optimush.png') }}";
                },
            }
        ]
    } );

    $('#table_r_filiais tbody').on('click', 'td.details-control', function () {

        var tr = $(this).closest('tr');
        var row = table.row( tr );
        var id = $(this).attr('id');

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data(), id) ).show();
            tr.addClass('shown');
        }
    } );
} );
