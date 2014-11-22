$(document).ready(function() {


    $("#mod-app").submit(function(event) {
        event.preventDefault();

        $.post('application/submit.php',

            $(this).serialize(),

            function(data) {
                if (data['success'] === true) {
                    $('.message').html('<div class="alert alert-success" role="alert">Your application was successfully submitted.</div>');
                    $('.alert').slideDown();
                    $('#mod-app').fadeOut();

                } else if (data['success'] === false) {
                    $('.message').html("<div class=\"alert alert-danger\" role=\"alert\">" + data['message'] + "</div>");
                    $('.email').addClass('has-error');
                    $('.submit').addClass('disabled');
                    $('input[type="submit"]').attr('disabled', 'disabled');
                    $('.alert').slideDown();
                }

            }, 'json');
    });


    $(".email").on("input", function() {
        $('input[type="submit"]').removeAttr('disabled');
    });
});