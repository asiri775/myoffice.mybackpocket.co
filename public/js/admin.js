$(document).ready(function () {
    $(document).on('submit', '.adminAjaxForm', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var form = $(this);
        var actionUrl = form.attr('action');
        var formData = form.serialize();

        $.ajax({
            url: actionUrl,
            type: form.attr('method'),
            data: formData,
            success: function (response) {
                if (response.success) {
                    window.webAlerts.push({
                        type: "success",
                        message: response.message
                    });
                    window.location.reload();
                } else {
                    window.webAlerts.push({
                        type: "error",
                        message: response.error
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                window.webAlerts.push({
                    type: "error",
                    message: errorThrown?.message ?? "Failed to send your request"
                });
            }
        });

    });
});
