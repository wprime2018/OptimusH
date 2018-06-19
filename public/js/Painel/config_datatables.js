$(function () {
    $('#example1').DataTable({
        'paging'      : true,
        'fixedHeader' : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            {
                extend: 'excelHtml5',
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                },
                footer: true,
                titleAttr: 'Exporta a EXCEL',
                text: '<i class="fa fa-file-excel-o"></i>',
            },
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example11').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            {
                extend: 'excelHtml5',
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                },
                footer: true,
                titleAttr: 'Exporta a EXCEL',
                text: '<i class="fa fa-file-excel-o"></i>',
            },
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example12').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'fixedHeader' : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            {
                extend: 'excelHtml5',
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                },
                footer: true,
                titleAttr: 'Exporta a EXCEL',
                text: '<i class="fa fa-file-excel-o"></i>',
            },
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example13').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'fixedHeader' : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            {
                extend: 'excelHtml5',
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                },
                footer: true,
                titleAttr: 'Exporta a EXCEL',
                text: '<i class="fa fa-file-excel-o"></i>',
            },
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example14').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            {
                extend: 'excelHtml5',
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                },
                footer: true,
                titleAttr: 'Exporta a EXCEL',
                text: '<i class="fa fa-file-excel-o"></i>',
            },
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example15').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            {
                extend: 'excelHtml5',
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="G"], row c[r^="H"]', sheet).attr( 's', 57);
                },
                footer: true,
                titleAttr: 'Exporta a EXCEL',
                text: '<i class="fa fa-file-excel-o"></i>',
            },
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example16').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example17').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example18').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example19').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),

    $('#example110').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }

        ]
    }),
 
    $('#example111').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'fixedHeader' : true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'responsive'  : true,
        'dom': '<l<B>f<t>ip>',
        'buttons': [
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                title: 'Produtos - OptimusH'
            }
        ]
    })
})
