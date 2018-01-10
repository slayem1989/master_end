$(document).ready(function() {
    var messageLogo     = $("#file-error");

    var idBoutonValider = $("#whitelabel_backofficebundle_client__valider");
    var fileExtension   = ["image/jpg", "image/jpeg", "image/png"];

    /* *************************************************
                        LOGO
    ************************************************* */
    var idLogo = $('#whitelabel_backofficebundle_client__client_information_logo');

    idLogo.change(function() {
        validateFile(this.files[0], messageLogo, idBoutonValider);

        var elt_file = $('#value_file').val();
        if ('true' == elt_file) {
            idBoutonValider.prop('disabled', false);
        } else {
            idBoutonValider.prop('disabled', true);
        }
    });

    /* *************************************************
                        FUNCTION
    ************************************************* */
    function validateFile(file, message, idSubmit) {
        var isFileValid = true;
        var normalSize = '5242880';
        message.empty();

        if (file.size > normalSize) {
            message.append('<li>Le fichier ' + file.name + ' est trop volumineux (' + formatFileSize(file) + '). Sa taille ne doit pas dépasser 5 MB. </li>').css('color', 'red');
            isFileValid = false;
        }

        if (fileExtension.indexOf(file.type) <= -1) {
            message.append('<li>Le type du fichier est invalide. Les types autorisés sont ".jpg", ".jpeg", ".png". </li>').css('color', 'red');
            isFileValid = false;
        }

        if (true != isFileValid) {
            $('#value_file').val('false');
        } else {
            $('#value_file').val('true');
        }
    }

    function formatFileSize(file) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (0 == file.size) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(file.size) / Math.log(1024)));

        return Math.round(file.size / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
});
