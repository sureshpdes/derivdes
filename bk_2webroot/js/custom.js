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
$( function() {
    $( "#from_datepicker " ).datepicker();
    $( "#to_datepicker" ).datepicker();
  } );