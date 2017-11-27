$(document).ready(function() {
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("password_repeat");

    $('#password_repeat').bind('paste', function (e) {
        e.preventDefault();
    });

    function validatePassword()
    {
        if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Les mots de passe ne sont pas identique.");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

    function validateFormatPassword()
    {
        var reg = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
        if (reg.test(password.value)) {
        //if (document.getElementById("result").classList.contains('strong')) {
            password.setCustomValidity('');
        } else {
            password.setCustomValidity('Le mot de passe n\'est pas assez sécurisé.');
        }
    }
    password.onchange = validateFormatPassword;

    ////////////////////////////////////////////////////////////////////

    $('#password').keyup(function(){
        $('#result').html(checkStrength($('#password').val()))
    });

    function checkStrength(password)
    {
        var strength = 0;

        if (password.length < 6) {
            $('#result').removeClass();
            $('#result').addClass('short');
            return 'Mot de passe trop court'
        }

        if (password.length > 7) strength += 1;
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

        if (strength < 2) {
            $('#result').removeClass();
            $('#result').addClass('weak');
            return 'Niveau faible'
        } else if (strength == 2 ) {
            $('#result').removeClass();
            $('#result').addClass('good');
            return 'Niveau bon'
        } else {
            $('#result').removeClass();
            $('#result').addClass('strong');
            return 'Niveau fort'
        }
    }

    ////////////////////////////////////////////////////////////////////

    $('input[type=password]#password').keyup(function() {
        var pswd = $(this).val();

        //validate letter
        if (pswd.match(/[a-z]/)) {
            $('#pswd_letter').removeClass('invalid').addClass('valid');
        } else {
            $('#pswd_letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if (pswd.match(/[A-Z]/)) {
            $('#pswd_capital').removeClass('invalid').addClass('valid');
        } else {
            $('#pswd_capital').removeClass('valid').addClass('invalid');
        }

        //validate number
        if (pswd.match(/\d/)) {
            $('#pswd_number').removeClass('invalid').addClass('valid');
        } else {
            $('#pswd_number').removeClass('valid').addClass('invalid');
        }

        //validate length
        if (pswd.length < 8) {
            $('#pswd_length').removeClass('valid').addClass('invalid');
        } else {
            $('#pswd_length').removeClass('invalid').addClass('valid');
        }
    });
    /*
    .focus(function() {
        //$("#pswd_info").show();
        $("#pswd_info").fadeTo(1400, 1).slideDown(700, function(){
            $("#pswd_info").show();
        });
    })
    .blur(function() {
        //$("#pswd_info").hide();
        $("#pswd_info").fadeTo(500, 0).slideUp(700, function(){
            $("#pswd_info").hide();
        });
    });
    */
});
