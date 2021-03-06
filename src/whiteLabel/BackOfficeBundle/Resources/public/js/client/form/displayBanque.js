$(document).ready(function() {
    var $container = $('div#whitelabel_backofficebundle_client__banque');

    // Cacher les label générés automatiquement et mise en forme
    $container.children('div').children('label').addClass('hidden');
    $container.children('div').addClass('wrapper_banque').children('div').addClass('client_banque');

    // On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
    var index = $container.find('div.wrapper_banque').length;

    // On passe les champs en Requis au chargement de la page EDITION
    var elt_banque = document.querySelectorAll('.client_banque');
    $.each(elt_banque, function () {
        // Nom
        $(this).children('div:nth-child(1)').children('label').addClass('required');
        $(this).children('div:nth-child(1)').children('input').attr('required', true);

        // RIB
        $(this).children('div:nth-child(2)').children('label').addClass('required');
        $(this).children('div:nth-child(2)').children('input').attr('required', true);

        // IBAN
        $(this).children('div:nth-child(3)').children('label').addClass('required');
        $(this).children('div:nth-child(3)').children('input').attr('required', true);

        // BIC
        $(this).children('div:nth-child(4)').children('label').addClass('required');
        $(this).children('div:nth-child(4)').children('input').attr('required', true);

        // Titulaire
        $(this).children('div:nth-child(5)').children('label').addClass('required');
        $(this).children('div:nth-child(5)').children('input').attr('required', true);
    });

    /* *****************************************************************
     ********************************************************************
     On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
     ********************************************************************
     *******************************************************************/
    $('#button_addBanque').click(function(e) {
        addBanque($container);

        // Cacher les label générés automatiquement et mise en forme
        $container.children('div').children('label').addClass('hidden');
        $container.children('div').addClass('wrapper_banque').children('div').addClass('client_banque');

        // On passe les champs en Requis au chargement de la page CLICK
        var elt_banque = document.querySelectorAll('.client_banque');
        $.each(elt_banque, function () {
            // Nom
            $(this).children('div:nth-child(1)').children('label').addClass('required');
            $(this).children('div:nth-child(1)').children('input').attr('required', true);

            // RIB
            $(this).children('div:nth-child(2)').children('label').addClass('required');
            $(this).children('div:nth-child(2)').children('input').attr('required', true);

            // IBAN
            $(this).children('div:nth-child(3)').children('label').addClass('required');
            $(this).children('div:nth-child(3)').children('input').attr('required', true);

            // BIC
            $(this).children('div:nth-child(4)').children('label').addClass('required');
            $(this).children('div:nth-child(4)').children('input').attr('required', true);

            // Titulaire
            $(this).children('div:nth-child(5)').children('label').addClass('required');
            $(this).children('div:nth-child(5)').children('input').attr('required', true);
        });

        // Checking IBAN
        $('.control_iban').on('onchange blur keyup load', function () {
            var elt_iban_init = document.querySelectorAll('.control_iban');
            validateAllIBAN(elt_iban_init);
        });

        e.preventDefault();
        return false;
    });

    /* *****************************************************************
     ********************************************************************
     On ajoute un premier champ automatiquement s'il n'en existe pas déjà un.
     ********************************************************************
     *******************************************************************/
    if (index == 0) {
        addFirstBanque($container);

        // Cacher les label générés automatiquement et mise en forme
        $container.children('div').children('label').addClass('hidden');
        $container.children('div').addClass('wrapper_banque').children('div').addClass('client_banque');

        // On passe les champs en Requis au chargement de la page CREATION
        var elt_banque = document.querySelectorAll('.client_banque');
        $.each(elt_banque, function () {
            // Nom
            $(this).children('div:nth-child(1)').children('label').addClass('required');
            $(this).children('div:nth-child(1)').children('input').attr('required', true);

            // RIB
            $(this).children('div:nth-child(2)').children('label').addClass('required');
            $(this).children('div:nth-child(2)').children('input').attr('required', true);

            // IBAN
            $(this).children('div:nth-child(3)').children('label').addClass('required');
            $(this).children('div:nth-child(3)').children('input').attr('required', true);

            // BIC
            $(this).children('div:nth-child(4)').children('label').addClass('required');
            $(this).children('div:nth-child(4)').children('input').attr('required', true);

            // Titulaire
            $(this).children('div:nth-child(5)').children('label').addClass('required');
            $(this).children('div:nth-child(5)').children('input').attr('required', true);
        });
    } else {
        var k = 0;
        // S'il existe déjà des contacts, on ajoute un lien de suppression pour chacun d'entre eux
        $container.children('div').each(function() {
            addDeleteLink($(this), k);
            k++;
        });
    }

    // Checking LOGO
    $('#whitelabel_backofficebundle_client__logo').change(function() {
        validateAllLogo(this.files[0]);
    });

    // Checking IBAN
    $('.control_iban').on('change blur keyup load', function () {
        var elt_iban_init = document.querySelectorAll('.control_iban');
        validateAllIBAN(elt_iban_init);
    });

    /* *****************************************************************
     ********************************************************************
     La fonction qui ajoute un premier formulaire Partenaire_contactType.
     ********************************************************************
     *******************************************************************/
    function addFirstBanque($container) {
        var template = $container.attr('data-prototype')
            //.replace(/__name__label__/g, 'Contact n°' + (index+1))
                .replace(/__name__label__/g, '')
                .replace(/__name__/g,        index)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        index++;
    }

    /* *****************************************************************
     ********************************************************************
     La fonction qui ajoute un formulaire Partenaire_contactType.
     ********************************************************************
     *******************************************************************/
    function addBanque($container) {
        var template = $container.attr('data-prototype')
            //.replace(/__name__label__/g, 'Contact n°' + (index+1))
                .replace(/__name__label__/g, '')
                .replace(/__name__/g,        index)
        ;

        // On crée un objet jquery qui contient ce template
        var $prototype = $(template);

        // On ajoute au prototype un lien pour pouvoir supprimer la banque
        addDeleteLink($prototype, 99);

        // On ajoute le prototype modifié à la fin de la balise <div>
        $container.append($prototype);

        index++;
    }

    /* *****************************************************************
     ********************************************************************
     La fonction qui ajoute un lien de suppression d'un contact.
     ********************************************************************
     *******************************************************************/
    function addDeleteLink($prototype, k) {
        if (0 != k) {
            var $deleteLink = $('<p class="wrapper_deleteBanque"><a href="#" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a></p>');

            // Ajout du lien
            $prototype.append($deleteLink);

            // Ajout du listener sur le clic du lien pour effectivement supprimer la banque
            $deleteLink.click(function (e) {
                $prototype.remove();

                // Checking IBAN
                var elt_iban_init = document.querySelectorAll('.control_iban');
                validateAllIBAN(elt_iban_init);

                e.preventDefault();
                return false;
            });
        }
    }

    /* *************************************************
                    FUNCTION CONTROL IBAN
    ************************************************* */
    function validateAllIBAN(element) {
        var idBoutonValider = $("#whitelabel_backofficebundle_client__valider");
        var errorFlagIBAN = false;
        var errorFlagLogo = false;
        var numberBanque = 0;

        $.each(element, function(index, input) {
            numberBanque++;
            errorFlagIBAN = validateIBAN(input, numberBanque, errorFlagIBAN);
        });
        isErrorLogo = validateLogo($('#whitelabel_backofficebundle_client__logo')[0].files[0], errorFlagLogo);

        if (true == errorFlagIBAN || true == isErrorLogo) {
            idBoutonValider.prop('disabled', true);
        } else {
            idBoutonValider.prop('disabled', false);
        }
    }

    function validateIBAN(input, numberBanque, errorFlagIBAN) {
        var messageIBAN = $("#custom-control_IBAN");

        if ('1' == numberBanque) {
            messageIBAN.empty();
        }

        var iban_input = input.value.toUpperCase();
        var iban = String(iban_input).toUpperCase().replace(/[^A-Z0-9]/g, '');  // keep only alphanumeric characters
        var code = iban.match(/^([A-Z]{2})(\d{2})([A-Z\d]+)$/);                 // match and capture (1) the country code, (2) the check digits, and (3) the rest
        var digits;

        var CODE_LENGTHS = {
            AD: 24, AE: 23, AT: 20, AZ: 28, BA: 20, BE: 16, BG: 22, BH: 22, BR: 29,
            CH: 21, CR: 21, CY: 28, CZ: 24, DE: 22, DK: 18, DO: 28, EE: 20, ES: 24,
            FI: 18, FO: 18, FR: 27, GB: 22, GI: 23, GL: 18, GR: 27, GT: 28, HR: 21,
            HU: 28, IE: 22, IL: 23, IS: 26, IT: 27, JO: 30, KW: 30, KZ: 20, LB: 28,
            LI: 21, LT: 20, LU: 20, LV: 21, MC: 27, MD: 24, ME: 22, MK: 19, MR: 27,
            MT: 31, MU: 30, NL: 18, NO: 15, PK: 24, PL: 28, PS: 29, PT: 25, QA: 29,
            RO: 24, RS: 22, SA: 24, SE: 24, SI: 19, SK: 24, SM: 27, TN: 24, TR: 26
        };

        // check syntax and length
        if (!code || iban.length !== CODE_LENGTHS[code[1]]) {
            messageIBAN.append('<li>Banque ' + numberBanque + ' : L\'IBAN est incorrecte.</li>').css('color', 'red');
            input.style.backgroundColor = "rgba(217,83,79,0.4)";
            errorFlagIBAN = true;
        } else {
            // rearrange country code and check digits, and convert chars to ints
            digits = (code[3] + code[1] + code[2]).replace(/[A-Z]/g, function (letter) {
                return letter.charCodeAt(0) - 55;
            });

            // final check
            if (1 === mod97(digits)) {
                input.style.backgroundColor = "rgba(92,184,92,0.4)";
            } else {
                messageIBAN.append('<li>Banque ' + numberBanque + ' : L\'IBAN est incorrecte.</li>').css('color', 'red');
                input.style.backgroundColor = "rgba(217,83,79,0.4)";
                errorFlagIBAN = true;
            }
        }

        return errorFlagIBAN;
    }

    function mod97(string) {
        var checksum = string.slice(0, 2),
            fragment;

        for(var offset = 2; offset < string.length; offset += 7) {
            fragment = String(checksum) + string.substring(offset, offset + 7);
            checksum = parseInt(fragment, 10) % 97;
        }

        return checksum;
    }

    /* *************************************************
                    FUNCTION CONTROL FILE
    ************************************************* */
    function validateAllLogo(file) {
        var idBoutonValider = $("#whitelabel_backofficebundle_client__valider");
        var errorFlagIBAN = false;
        var errorFlagLogo = false;
        var numberBanque = 0;

        var elt_iban_init = document.querySelectorAll('.control_iban');
        $.each(elt_iban_init, function(index, input) {
            numberBanque++;
            errorFlagIBAN = validateIBAN(input, numberBanque, errorFlagIBAN);
        });
        isErrorLogo = validateLogo(file, errorFlagLogo);

        if (true == errorFlagIBAN || true == isErrorLogo) {
            idBoutonValider.prop('disabled', true);
        } else {
            idBoutonValider.prop('disabled', false);
        }
    }

    function validateLogo(file, errorFlagLogo) {
        //var file = $('#whitelabel_backofficebundle_client__logo');
        var fileExtension   = ["image/jpg", "image/jpeg", "image/png"];
        var messageLogo = $("#file-error");
        var normalSize = '5242880';

        messageLogo.empty();

        if (file.size > normalSize) {
            messageLogo.append('<li>Le fichier ' + file.name + ' est trop volumineux (' + formatFileSize(file) + '). Sa taille ne doit pas dépasser 5 MB. </li>').css('color', 'red');
            errorFlagLogo = true;
        }

        if (fileExtension.indexOf(file.type) <= -1) {
            messageLogo.append('<li>Le type du fichier est invalide. Les types autorisés sont ".jpg", ".jpeg", ".png". </li>').css('color', 'red');
            errorFlagLogo = true;
        }

        return errorFlagLogo;
    }

    function formatFileSize(file) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (0 == file.size) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(file.size) / Math.log(1024)));

        return Math.round(file.size / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
});
