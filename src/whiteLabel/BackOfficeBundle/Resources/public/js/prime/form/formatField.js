$(document).ready(function() {
    /* Formatter */
    $('#blacklabel_importbundle_import_prime_numero').formatter(format_numero);
    $('#blacklabel_importbundle_import_prime_codePostalFacturation').formatter(format_code_postal);
    $('#blacklabel_importbundle_import_prime_codePostalChantier').formatter(format_code_postal);
    $('#blacklabel_importbundle_import_prime_telephone').formatter(format_telephone);
});
