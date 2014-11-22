$(document).ready(function() {


    $("#mod-app").submit(function(event) {
        event.preventDefault();

        $.post('application/submit.php',

            $(this).serialize(),

            function(data) {
                if (data['success'] === true) {
                    $('.message').html('<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="sr-only"> Success! </span><strong>Success!</strong> Your application was successfully submitted.</div>');
                    $('.alert').slideDown();
                    $('#sub-form').fadeOut();

                } else if (data['success'] === false) {
                    $('.message').html("<div class=\"alert alert-danger\" role=\"alert\"><span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span><span class=\"sr-only\"> Error:</span><strong>Error!</strong> " + data['message'] + "</div>");
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