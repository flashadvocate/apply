$(document).ready(function() {
    $("#mod-app").submit(function(event) {
        event.preventDefault();

        $.post('application/submit.php',

            $(this).serialize(),

            function(data) {
                if (data['success'] === true) {
                    $('.message').append('<div class="alert alert-success" role="alert">Your application was successfully submitted.</div>');
                    $('.alert').slideDown();
                    $('#mod-app').fadeOut();

                } else if (data['success'] === false) {
                    $('.message').append("<div class=\"alert alert-danger\" role=\"alert\">" + data['message'] + "</div>");
                    $('.alert').slideDown();
                }

            }, 'json');
    });
});