$(document).ready(function() {
    $('#table_stock').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, 100]],
        "pageLength": 25,
        "order": [[ 1, "asc" ]]
    });
} );
