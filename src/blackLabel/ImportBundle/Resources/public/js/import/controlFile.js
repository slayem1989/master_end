$(document).ready(function() {
    var messageImport = $("#file-error");

    var idBoutonValider = $("#blacklabel_importbundle_import_valider");
    var fileExtension   = ["application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"];

    /* *************************************************
                    IMPORT FILE
    ************************************************* */
    var idImport = $('#blacklabel_importbundle_import_file');

    idImport.change(function() {
        validateFile(this.files[0], messageImport, idBoutonValider);
    });

    /* *************************************************
                        FUNCTION
    ************************************************* */
    function validateFile(file, message, idSubmit) {
        var errorFlag = false;
        message.empty();
        var normalSize = '20971520';
        if (file.size > normalSize) {
            message.append('<li>Le fichier ' + file.name + ' est trop volumineux (' + formatFileSize(file) + '). Sa taille ne doit pas dépasser 20 MB. </li>').css('color', 'red');
            errorFlag = true;
        }

        if (fileExtension.indexOf(file.type) <= -1) {
            message.append('<li>Le type du fichier est invalide. Les types autorisés sont ".xls", ".xlsx". </li>').css('color', 'red');
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
