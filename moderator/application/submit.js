$(document).ready(function() {
    $("#mod-app").submit(function(event) {
        event.preventDefault();

        $.post('application/submit.php',

            $(this).serialize(),

            function(data) {
                if (data['success'] === true) {
                    console.log(data['message']);

                } else if (data['success'] === false) {
                    console.log(data['message']);
                }

            }, 'json');
    });
});