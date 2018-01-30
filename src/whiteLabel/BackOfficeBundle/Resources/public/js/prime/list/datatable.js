$(document).ready(function() {
    var elt_clientId = $('input#data_clientId').val();

    // Declare DataTable
    var table = $('#table_prime').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, 100]],
        "pageLength": 25,
        "order": [[ 1, "asc" ]],
        "orderCellsTop": true,
        "scrollX": false,
        "stateSave": true,
        "stateDuration": -1,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: 'ajax/' + elt_clientId,
            dataType: 'JSON',
            type: 'POST'
        },
        "columns": [
            {"data": "lotNumero", "class": "breakWord"},
            {"data": "primeNumero", "class": "breakWord"},
            {"data": "primeIdentifiant", "class": "breakWord"},
            {"data": "primeVille", "class": "breakWord"},
            {"data": "primeType", "class": "breakWord textCenter"},
            {"data": "primeEmail", "class": "breakWord"},
            {"data": "primeTelephone", "class": "breakWord"},
            {"data": "primeStatut", "class": "breakWord"},
            {"data": "primeDateIntegration", "class": "breakWord textCenter"},
            {"data": "primeOnglet", "class": "breakWord"},
            {"data": "action", "class": "breakWord textCenter", "searchable": false, "sortable": false}
        ],
        "columnDefs": [
            {
                "targets": 1,
                "render": function(data, type, row) {
                    return '<a href="../read/' + elt_clientId + '/' + row['primeId'] + '"\n' +
                        'class="tooltip-consultation"\n' +
                        'data-toggle="tooltip"\n' +
                        'data-placement="bottom"\n' +
                        'data-container="body"\n' +
                        'title="Consulter la Prime">' +
                        row['primeNumero'] +
                        '</a>'
                    ;
                }
            },
            {
                "targets": 10,
                "render": function(data, type, row) {
                    var html = '<ul>';
                    var buttonColor = '';
                    var arrayCommentaire = row['commentaire'].split("|");
                    $.map(arrayCommentaire, function (item) {
                        var arrayItem = item.split("<>");
                        if (arrayItem[0] && arrayItem[1] && arrayItem[2]) {
                            html += '<p class="textBold textLeft">Le ' + arrayItem[2] + ' par ' + arrayItem[1] + ':</p>' +
                                    '<p class="textLeft">' + arrayItem[0] + '</p>' +
                                    '<hr>'
                            ;
                            buttonColor = 'danger';
                        } else {
                            html += '<p class="textBold textCenter">Aucun commentaire.</p>';
                            buttonColor = 'primary';
                        }
                    });
                    html += '</ul>';

                    var countCommentaire = 0;
                    if ('danger' == buttonColor) {
                        countCommentaire = arrayCommentaire.length;
                    }

                    return '<a href="../update/RIB/' + elt_clientId + '/' + row['primeId'] + '"\n' +
                        'class="tooltip-updateRIB btn btn-primary btn-xs"\n' +
                        'role="button"\n' +
                        'data-toggle="tooltip"\n' +
                        'data-placement="bottom"\n' +
                        'data-container="body"\n' +
                        'title="Modifier le RIB">' +
                        '<i class="glyphicon glyphicon-euro"></i>' +
                        '</a>' +
                        '&nbsp;' +
                        '<a href="../update/address/' + elt_clientId + '/' + row['primeId'] + '"\n' +
                        'class="tooltip-updateAdresse btn btn-primary btn-xs"\n' +
                        'role="button"\n' +
                        'data-toggle="tooltip"\n' +
                        'data-placement="bottom"\n' +
                        'data-container="body"\n' +
                        'title="Modifier l\'Adresse">' +
                        '<i class="glyphicon glyphicon-home"></i>' +
                        '</a>' +
                        '&nbsp;' +
                        '<a href="../../historique/prime/list/' + elt_clientId + '/' + row['primeId'] + '"\n' +
                        'class="tooltip-historique btn btn-primary btn-xs"\n' +
                        'role="button"\n' +
                        'data-toggle="tooltip"\n' +
                        'data-placement="bottom"\n' +
                        'data-container="body"\n' +
                        'title="Afficher l\'historique de la prime">' +
                        '<i class="glyphicon glyphicon-time"></i>' +
                        '</a>' +
                        '&nbsp;' +
                        '<span data-toggle="modal" data-target="#modal_commentaire_' + row['primeId'] + '">' +
                        '<button type="button"\n' +
                        'class="tooltip-commentaire btn btn-' + buttonColor + ' btn-xs"\n' +
                        'data-toggle="tooltip"\n' +
                        'data-placement="bottom"\n' +
                        'data-container="body"\n' +
                        'title="Afficher les Commentaires de la Prime">' +
                        '<i class="glyphicon glyphicon-comment"></i>' +
                        '</button>'+
                        '</span>'+
                        '&nbsp;' +
                        '<div class="modal fade modalBackdrop modal_commentaire" id="modal_commentaire_' + row['primeId'] + '" tabindex="-1" role="dialog" data-backdrop="false">' +
                        '<div class="modal-dialog" role="document">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<legend class="modal-title textCenter"><span class="legend-icon"></span>&nbsp;Prime: ' + row['primeNumero'] + '</legend>' +
                        '</div>' +
                        '<div class="modal-body">' +
                            '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloc-commentaire">' +
                                '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloc bloc-commentaireForm">' +
                                '<fieldset>' +
                                    '<legend><span class="legend-icon"></span>&nbsp;Cr&eacute;er un commentaire</legend>' +
                                    '<form class="form-horizontal" name="blacklabel_commentairebundle_commentaire_prime" method="post">' +
                                        '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"></div>' +
                                        '<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 form-error"></div>' +
                                        '<div class="form-group noPadding">' +
                                            '<label class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label required" for="blacklabel_commentairebundle_commentaire_prime_content">' +
                                            'Contenu :' +
                                            '</label>' +
                                            '<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">' +
                                                '<textarea id="blacklabel_commentairebundle_commentaire_prime_content"\n' +
                                                'class="form-control"\n' +
                                                'name="blacklabel_commentairebundle_commentaire_prime[content]"\n' +
                                                'required="required"\n' +
                                                'placeholder="Entrez le contenu du commentaire (limité à 245 caractères)"\n' +
                                                'maxlength="245">' +
                                                '</textarea>' +
                                            '</div>' +
                                        '</div>' +
                                        '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloc marginTop20">' +
                                            '<div class="form-group">' +
                                                '<button id="blacklabel_commentairebundle_commentaire_prime_enregistrer"\n' +
                                                'class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xs-offset-0 col-sm-offset-4 col-md-offset-4 col-lg-offset-4 btn btn-xs btn-success"\n' +
                                                'type="submit"\n' +
                                                'name="blacklabel_commentairebundle_commentaire_prime[enregistrer]">' +
                                                'Enregistrer' +
                                                '</button>' +
                                            '</div>' +
                                            '<input id="blacklabel_commentairebundle_commentaire_prime_prime_id"\n' +
                                            'name="blacklabel_commentairebundle_commentaire_prime[prime_id]"\n' +
                                            'value="' + row['primeId'] + '"\n' +
                                            'required="required"\n' +
                                            'placeholder=""\n' +
                                            'readonly="readonly"\n' +
                                            'type="hidden">' +
                                        '</div>' +
                                    '</form>' +
                                '</fieldset>' +
                                '</div>' +
                                '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloc bloc-commentaireList">' +
                                '<fieldset>' +
                                    '<legend><span class="legend-icon"></span>&nbsp;Liste des commentaires (' + countCommentaire + ')</legend>' +
                                    html +
                                '</fieldset>' +
                                '</div>' +
                            '</div>' +
                            '&nbsp;' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    ;
                }
            }
        ],
        "fnDrawCallback": function() {
            $('.tooltip-consultation').tooltip();
            $('.tooltip-updateRIB').tooltip();
            $('.tooltip-updateAdresse').tooltip();
            $('.tooltip-historique').tooltip();
            $('.tooltip-commentaire').tooltip();
        },
        "initComplete": function() {
            var api = this.api();

            // Apply text filter
            api.columns('.with_search').every(function() {
                var title = $(this.footer()).text();
                $(this.footer()).html('<input type="text" placeholder="' + title + '" />');

                var that = this;
                $('input', this.footer()).on('keyup change', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });

            // Apply select filter
            api.columns('.with_select').every(function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? val : '', true, false)
                            .draw();
                    });
                column.data().unique().sort().each(function(d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });

            // Restore state
            var state = table.state.loaded();
            if (state) {
                table.columns().eq(0).each(function (colIdx) {
                    var colSearch = state.columns[colIdx].search;
                    var indexChild = parseInt(colIdx+1);

                    $('tr#filterrow th:nth-child( ' + indexChild + ') input').val(colSearch.search);
                    $('tr#filterrow th:nth-child( ' + indexChild + ') select').val(colSearch.search);
                });
            }
        }
    });

    // Clear column filter
    $('#clear_search_input').on('click',function() {
        $('tr#filterrow th input').val('').change();
        $('tr#filterrow th select').val('').change();
    });
});
