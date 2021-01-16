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

    var date = new Date();
    date.setMonth(date.getMonth()-3);
    date.setDate(1);

    $('.input-daterange').datepicker({
        startDate: date,
        endDate: new Date(),
        language: "es",
        todayHighlight: true,
    });
});
$(".main-panel").perfectScrollbar('update');
