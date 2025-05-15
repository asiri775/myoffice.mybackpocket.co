function showLoader() {
    var loader = document.getElementById('myLoader');
    loader.classList.add("show"); 
}


function hideLoader() {
    var loader = document.getElementById('myLoader');
    loader.classList.remove("show");  
}



jQuery(function ($) {

    //Login
    $('.bravo-form-login [type=submit]').click(function (e) {
        showLoader()
        redirectUrl = $(location).attr("origin")+'/m/home';
      
        e.preventDefault();
        let form = $(this).closest('.bravo-form-login');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            'url': bookingCore.routes.login,
            'data': {
                'email': form.find('input[name=email]').val(),
                'password': form.find('input[name=password]').val(),
                'remember': form.find('input[name=remember]').is(":checked") ? 1 : '',
                'g-recaptcha-response': form.find('[name=g-recaptcha-response]').val(),
                'redirect': form.find('input[name=redirect]').val()
            },
            'type': 'POST',
            beforeSend: function () {
                form.find('.error').hide();
                form.find('.icon-loading').css("display", 'inline-block');
            },
            success: function (data) {
                form.find('.icon-loading').hide();
                if (data.error === true) {
                    if (data.messages !== undefined) {
                        for (var item in data.messages) {
                            var msg = data.messages[item];
                            form.find('.error-' + item).show().text(msg[0]);
                        }
                    }
                    if (data.messages.message_error !== undefined) {
                        form.find('.message-error').show().html('<div class="alert alert-danger">' + data.messages.message_error[0] + '</div>');
                    }
                }
                if (typeof data.redirect !== 'undefined' && data.redirect) {
                    // window.location.href = data.redirect
                    window.location.href = redirectUrl
                }
                hideLoader()
            }
        });
    });


    //register guest
    $('.bravo-form-register [type=submit]').click(function (e) {
        showLoader();

        redirectUrl = $(location).attr("origin")+'/m/home';
        
        e.preventDefault();
        let form = $(this).closest('.bravo-form-register');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            'url': bookingCore.routes.register,
            'data': {
                'email': form.find('input[name=email]').val(),
                'password': form.find('input[name=password]').val(),
                'first_name': form.find('input[name=first_name]').val(),
                'last_name': form.find('input[name=last_name]').val(),
                'phone': form.find('input[name=phone]').val(),
                'term': form.find('input[name=term]').is(":checked") ? 1 : '',
                'g-recaptcha-response': form.find('[name=g-recaptcha-response]').val(),
                'platform' : 'mobile',
            },
            'type': 'POST',
            beforeSend: function () {
                form.find('.error').hide();
                form.find('.icon-loading').css("display", 'inline-block');
            },
            success: function (data) {
                form.find('.icon-loading').hide();
                if (data.error === true) {
                    if (data.messages !== undefined) {
                        for (var item in data.messages) {
                            var msg = data.messages[item];
                            form.find('.error-' + item).show().text(msg[0]);
                        }
                    }
                    if (data.messages.message_error !== undefined) {
                        form.find('.message-error').show().html('<div class="alert alert-danger">' + data.messages.message_error[0] + '</div>');
                    }
                }
                if (data.redirect !== undefined) {
                    // window.location.href = data.redirect
                    window.location.href = redirectUrl

                }
                hideLoader()
            },
            error: function (e) {
                form.find('.icon-loading').hide();
                if (typeof e.responseJSON !== "undefined" && typeof e.responseJSON.message != 'undefined') {
                    form.find('.message-error').show().html('<div class="alert alert-danger">' + e.responseJSON.message + '</div>');
                }
                hideLoader()
            }
        });
    });

    //register as host
    $('.bravo-register-host [type=submit]').click(function (e) {
        e.preventDefault();
        let form = $(this).closest('.bravo-register-host');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            'url': bookingCore.routes.register,
            'data': {
                'email': form.find('input[name=email]').val(),
                'password': form.find('input[name=password]').val(),
                'first_name': form.find('input[name=first_name]').val(),
                'last_name': form.find('input[name=last_name]').val(),
                'phone': form.find('input[name=phone]').val(),
                'term': form.find('input[name=term]').is(":checked") ? 1 : '',
                'g-recaptcha-response': form.find('[name=g-recaptcha-response]').val(),
                'register_as': 'host',
                'platform' : 'mobile'
            },
            'type': 'POST',
            beforeSend: function () {
                form.find('.error').hide();
                form.find('.icon-loading').css("display", 'inline-block');
            },
            success: function (data) {
                form.find('.icon-loading').hide();
                if (data.error === true) {
                    if (data.messages !== undefined) {
                        for (var item in data.messages) {
                            var msg = data.messages[item];
                            form.find('.error-' + item).show().text(msg[0]);
                        }
                    }
                    if (data.messages.message_error !== undefined) {
                        form.find('.message-error').show().html('<div class="alert alert-danger">' + data.messages.message_error[0] + '</div>');
                    }
                }
                if (data.redirect !== undefined) {
                    window.location.href = data.redirect
                }
            },
            error: function (e) {
                form.find('.icon-loading').hide();
                if (typeof e.responseJSON !== "undefined" && typeof e.responseJSON.message != 'undefined') {
                    form.find('.message-error').show().html('<div class="alert alert-danger">' + e.responseJSON.message + '</div>');
                }
            }
        });
    });

    $('#register').on('show.bs.modal', function (event) {
        $('#login').modal('hide')
    })
    $('#login').on('show.bs.modal', function (event) {
        $('#register').modal('hide')
    });

});