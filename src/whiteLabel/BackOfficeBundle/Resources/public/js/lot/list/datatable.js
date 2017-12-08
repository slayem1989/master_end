$(document).ready(function() {
    $('#table_lot').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, 100]],
        "pageLength": 25,
        "order": [[ 0, "desc" ]]
    });
    
    /*
    var table = $('#table_lot').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, 100]],
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        orderCellsTop: true
    });

    // Clear all filter
    $('#clear_search_input').on('click', function () {
        $('tr#filterrow th[class="with_search"] input').val('').change();
    });
    $('#table_lot thead tr#filterrow th[class="with_search"]').each( function () {
        var title = '';
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });

    // Apply column filter
    $("#table_lot thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    });
    */
});
