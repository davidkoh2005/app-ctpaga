$(document).ready( function () {
    $('.main-panel').perfectScrollbar({suppressScrollX: true, maxScrollbarLength: 200}); 
    $('#productsModal').modal('hide'); 

    $('#table_id').DataTable({
        "ordering": false,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Transacciones",
            "infoEmpty": "Mostrando 0 to 0 of 0 Transacciones",
            "infoFiltered": "(Filtrado de _MAX_ total Transacciones)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Transacciones",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });

    

    $('#table_Rate').DataTable({
        "ordering": false,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Tasas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Tasas",
            "infoFiltered": "(Filtrado de _MAX_ total Tasas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Tasas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });

    $('#table_Deposits').DataTable({
        "ordering": false,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Depositos",
            "infoEmpty": "Mostrando 0 to 0 of 0 Depositos",
            "infoFiltered": "(Filtrado de _MAX_ total Depositos)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Depositos",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });

    var date = new Date();
    date.setMonth(date.getMonth()-4);
    date.setDate(1);

    $('#datepicker').datepicker({
        orientation: "bottom auto",
        startDate: date,
        endDate: new Date(),
        language: "es",
        autoclose: true,
        todayHighlight: true
    });

    $('#datepicker-admin').datepicker({
        orientation: "bottom auto",
        language: "es",
        autoclose: true,
        todayHighlight: true
    });

    $('#dateAlarm').datepicker({
        orientation: "bottom auto",
        startDate: date,
        language: "es",
        autoclose: true,
        todayHighlight: true
    });

    $('#btnPDF').on('click', function() {
        $('#statusFile').val("PDF");
        $('#payment-form').submit();
    });

    $('#btnExcel').on('click', function() {
        $('#statusFile').val("EXCEL");
        $('#payment-form').submit();
    });
});
