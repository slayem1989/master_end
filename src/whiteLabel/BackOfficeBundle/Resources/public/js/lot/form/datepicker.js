$(document).ready(function(){
    $.fn.datepicker.defaults.language = 'fr';

    var date = $('input[id="form_date"]');
    var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

    date.datepicker({
        language: 'fr',
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        weekStart: 1
    });
});
