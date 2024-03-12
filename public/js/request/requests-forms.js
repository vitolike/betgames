// *** START AUTH *** \\

// Login Form
$(document).on('click', '#buttonLoginAccount', function () {
    var emailValue = $('#emailInput').val();
    var passwordValue = $('#passwordInput').val();
    /*var loadingText = "Autenticando";

    var dots = 0;
    var loadingInterval = setInterval(function() {
        dots = (dots + 1) % 4;
        if (dots === 4) {
            dots = 0;
        }
        var text = loadingText + Array(dots + 1).join(".");
        $('#buttonLoginAccount').text(text).addClass("btn-disabled");
    }, 500);*/

    $.ajax({
        url: '/auth/login',
        type: 'post',
        data: {
            email: emailValue,
            password: passwordValue
        },
        success: function (data) {
            //clearInterval(loadingInterval);
            //$('#buttonLoginAccount').text('Entrar').attr("disabled", false).removeClass("btn-disabled");
            $.notify({
                type: data.type,
                message: data.msg
            });
            window.location = data.url;
            return true;
        },
        error: function (jqXHR, data) {
            //clearInterval(loadingInterval);
            //$('#buttonLoginAccount').text('Entrar').attr("disabled", false).removeClass("btn-disabled");
            //if (jqXHR.status === 400) {
            var errors = jqXHR.responseJSON.input_error;
            Object.keys(errors).forEach(function(key) {
                $('#inputError-' + key).text(errors[key]);
            });
            //}
            //$('#error').text(error);
            //console.log(data.responseText);
        }
    });
});

// Register
$(document).on('click', '#registerNewAccount', function () {
    var usernameInput = $('#usernameInput').val();
    var first_nameInput = $('#first_nameInput').val();
    var last_nameInput = $('#last_nameInput').val();
    var number_smsInput = $('#number_smsInput').val();
    var emailValue = $('#emailInputReg').val();
    var passwordValue = $('#passwordInputReg').val();

    setTimeout(function() {
        $.ajax({
            url: '/auth/register',
            type: 'post',
            data: {
                username: usernameInput,
                first_name: first_nameInput,
                last_name: last_nameInput,
                number_sms: number_smsInput,
                email: emailValue,
                password: passwordValue
            },
            success: function (data) {
                $.notify({
                    type: data.type,
                    message: data.msg
                });
                window.location = data.url;
                return true;
            },
            error: function (jqXHR, data) {
                var errors = jqXHR.responseJSON.input_error;
                Object.keys(errors).forEach(function (key) {
                    $('#inputErrorReg-' + key).text(errors[key]);
                });
            }
        });
    }, 900);
});

// *** END AUTH *** \\
