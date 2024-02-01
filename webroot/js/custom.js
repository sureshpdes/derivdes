$(document).ready(function () {
    $(".checkbox").click(function () {
        if ($(".checkbox:checked").length === $(".checkbox").length) {
            $(".select-all").prop("checked", true);
        } else {
            $(".select-all").prop("checked", false);
        }
    });
    $(".select-all").click(function () {
        $(".checkbox").prop("checked", $(this).prop("checked"));
    });
});

$(document).ready(function () {
    $(".show-btn").click(function () {
       $(".show-form").slideDown();
    });
    
});
$(function() {
    $("#from_datepicker").datepicker();
    $("#to_datepicker").datepicker();

    $('#modal_from_datepicker').datepicker({
        format: 'dd-mm-yyyy',
        container: '#modal_from_datepicker',
    });
    $('#modal_to_datepicker').datepicker({
        format: 'dd-mm-yyyy',
        container: '#modal_to_datepicker',
    });
  });