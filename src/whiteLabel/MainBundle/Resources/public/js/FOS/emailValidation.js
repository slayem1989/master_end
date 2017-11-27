$(document).ready(function() {
    var email = document.getElementById("email");
    var confirm_email = document.getElementById("email_repeat");

    $('#email_repeat').bind('paste', function (e) {
        e.preventDefault();
    });

    function validateEmail()
    {
        if(email.value != confirm_email.value) {
            confirm_email.setCustomValidity("Les adresses email ne sont pas identiques.");
        } else {
            confirm_email.setCustomValidity('');
        }
    }

    email.onchange = validateEmail;
    confirm_email.onkeyup = validateEmail;
});
