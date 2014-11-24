$(document).ready(function() {

    $("#mod-app").submit(function(event) {
        event.preventDefault();

        // $.post('/apply/src/application/actions/submit.php',
        $.post('src/application/actions/submit.php',

            $(this).serialize(),

            function(data) {
                if (data['success'] === true) {
                    $('.message').html('<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="sr-only"> Success! </span><strong>Success!</strong> We have received your application!</div>');
                    $('.intro').html('We appreciate your interest in becoming a member of the ASMDSS staff. Please allow 1-2 weeks for us to respond to you about your application. Even if your application is turned down, we will be in contact with you.<br /><br />If you have any additional questions, please send them to our <a href="mailto:devteam@asmdss.com">development team</a>.');
                    $('.alert').slideDown();
                    $('#sub-form, .submit').fadeOut();

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