$(document).ready(function(){
    $.fn.datepicker.defaults.language = 'fr';
    var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

    var date = $('input[id="form_date"]');
    var date_deny4 = $('input[id="form_deny4_date"]');
    var date_deny5 = $('input[id="form_deny5_date"]');

    date.datepicker({
        language: 'fr',
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1
    });

    date_deny4.datepicker({
        language: 'fr',
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1
    });

    date_deny5.datepicker({
        language: 'fr',
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1
    });
});
