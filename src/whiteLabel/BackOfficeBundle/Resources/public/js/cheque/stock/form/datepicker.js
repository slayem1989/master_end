$(document).ready(function(){
    $.fn.datepicker.defaults.language = 'fr';

    var date = $('input[id="whitelabel_backofficebundle_cheque_stock_dateReception"]');
    var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

    date.datepicker({
        language: 'fr',
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1
    });

    /* /////////////////////////////////////////////////////////////////
                        SET DEFAULT DATE FOR CREATE
    ///////////////////////////////////////////////////////////////// */
    if( !date.val() ) {
        var now = new Date();
        var jour = ('0'+now.getDate()).slice(-2);
        var mois = ('0'+(now.getMonth()+1)).slice(-2);
        var annee = now.getFullYear();

        var display_date = jour + "/" + mois + "/" + annee;

        date.val(display_date);
    }
});
