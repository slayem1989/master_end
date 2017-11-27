$(document).ready(function() {
    /* Formatter */
    $('#whitelabel_backofficebundle_client__client_adresseFacturation_codePostal').formatter(format_code_postal);

    ////////////////////////////////////////////////////////////////////

    /* Whitespace */
    var arrayId = [
        $("#whitelabel_backofficebundle_client__client_information_nom"),
        $("#whitelabel_backofficebundle_client__client_information_interlocuteur"),
        $("#whitelabel_backofficebundle_client__client_adresseFacturation_destinataire"),
        $("#whitelabel_backofficebundle_client__client_adresseFacturation_adresse"),
        $("#whitelabel_backofficebundle_client__client_adresseFacturation_codePostal"),
        $("#whitelabel_backofficebundle_client__client_adresseFacturation_ville"),
        $("#whitelabel_backofficebundle_client__client_adresseNoteDebit_destinataire"),
        $("#whitelabel_backofficebundle_client__client_adresseNoteDebit_adresse"),
        $("#whitelabel_backofficebundle_client__client_adresseNoteDebit_codePostal"),
        $("#whitelabel_backofficebundle_client__client_adresseNoteDebit_ville")
    ];

    $.each(arrayId, function () {
        $(this).on('blur', function() {
            $(this).val($(this).val().replace(/^\s\s*/, '').replace(/\s\s*$/, ''));
        });
    });
});
