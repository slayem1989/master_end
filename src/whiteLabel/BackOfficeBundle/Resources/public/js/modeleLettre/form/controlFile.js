$(document).ready(function() {
    var messageImport = $("#file-error");

    var idBoutonValider = $("#whitelabel_backofficebundle_modelelettre_valider");
    var fileExtension   = ["application/vnd.openxmlformats-officedocument.wordprocessingml.document"];

    /* *************************************************
                    IMPORT FILE
    ************************************************* */
    var idImport = $('#whitelabel_backofficebundle_modelelettre_file');

    idImport.change(function() {
        validateFile(this.files[0], messageImport, idBoutonValider);
    });

    /* *************************************************
                        FUNCTION
    ************************************************* */
    function validateFile(file, message, idSubmit) {
        var errorFlag = false;
        message.empty();
        var normalSize = '5242880';
        if (file.size > normalSize) {
            message.append('<li>Le fichier ' + file.name + ' est trop volumineux (' + formatFileSize(file) + '). Sa taille ne doit pas dépasser 5 MB. </li>').css('color', 'red');
            errorFlag = true;
        }

        if (fileExtension.indexOf(file.type) <= -1) {
            message.append('<li>Le type du fichier est invalide. Les types autorisés sont ".docx". </li>').css('color', 'red');
            errorFlag = true;
        }

        if (true == errorFlag) {
            idSubmit.prop('disabled', true);
        } else {
            idSubmit.prop('disabled', false);
        }
    }

    function formatFileSize(file) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (0 == file.size) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(file.size) / Math.log(1024)));

        return Math.round(file.size / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
});
