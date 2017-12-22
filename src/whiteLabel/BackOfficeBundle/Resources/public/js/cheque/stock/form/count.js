$(document).ready(function() {
    $("#whitelabel_backofficebundle_cheque_stock_first, #whitelabel_backofficebundle_cheque_stock_last").on("keydown keyup", function() {
        count();
    });

    function count() {
        var cheque_debut = parseInt(document.getElementById('whitelabel_backofficebundle_cheque_stock_first').value);
        var cheque_fin = parseInt(document.getElementById('whitelabel_backofficebundle_cheque_stock_last').value);

        var count_input = document.getElementById('whitelabel_backofficebundle_cheque_stock_count');
        var submit_button = document.getElementById('whitelabel_backofficebundle_cheque_stock_valider');

        if (cheque_fin >= cheque_debut) {
            var compteur = (cheque_fin-cheque_debut) + 1;

            if (!isNaN(compteur)) {
                count_input.value = compteur;
                submit_button.disabled = false;
            }
        } else {
            count_input.value = 0;
            submit_button.disabled = true;
        }
    }
});
