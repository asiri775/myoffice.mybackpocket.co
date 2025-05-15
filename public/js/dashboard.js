$(document).on("click", ".closeQuickViewBox", function () {
    $(".quickview-wrapper").removeClass("open").removeClass("openHover");
});

async function sharerRun(data) {
    try {
        await navigator.share({
            title: document.title,
            url: data
        });
        return true;
    } catch (err) {
        const queryParams = {
            subject: document.title,
            body: data
        };
        const queryString = Object.keys(queryParams).map((key) => `${encodeURIComponent(key)}=${encodeURIComponent(queryParams[key])}`).join("&");
        const baseUrl = 'mailto:example@example.com';
        const url = `${baseUrl}?${queryString}`;
        window.location.href = url;
    }
}

$(document).on("click", ".sharer", async function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    await sharerRun(url);
});

let CONFIRM_R_URL = null;

$(document).on('click', 'a[data-confirm]', function (e) {
    var obj = $(this);
    e.preventDefault();
    CONFIRM_R_URL = obj.attr("href");
    $("#confirm-dialog").modal("show");
    let confirmText = obj.attr("data-confirm");
    $("#confirm-dialog h4").html(confirmText);
    return false;
});

$(document).on("click", "#cancelConfirm", function () {
    $("#confirm-dialog").modal("hide");
});

$(document).on("click", "#confirmYesModal", function () {
    $("#confirm-dialog").modal("hide");
    if (CONFIRM_R_URL != null) {
        window.location.replace(CONFIRM_R_URL);
        CONFIRM_R_URL = null;
    }
});

//mobileNoFieldData
$(document).ready(function () {
    if ($(".mobileNoFieldData").length > 0) {

        for (let mobileElem of document.getElementsByClassName("mobileNoFieldData")) {

            var mobileElemParent = $(mobileElem).closest(".mobileNoFieldDataParent");
            var realElement = mobileElemParent.find(".real");

            const iti = window.intlTelInput(mobileElem, {
                nationalMode: false,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js",
                preferredCountries: ['ca', 'us'],
                initialCountry: 'ca'
            });

            const handleChange = () => {
                if (mobileElem.value) {
                    if (iti.isValidNumber()) {
                        // window.webAlerts.push({
                        //     type: "success",
                        //     message: "Phone no is valid"
                        // });
                        realElement.val(iti.getNumber());
                    } else {
                        window.webAlerts.push({
                            type: "error",
                            message: "Please enter a valid number."
                        });
                        // toastr.error("Please enter a valid number.");
                    }
                } else {
                    // toastr.error("Please enter a valid number");
                    window.webAlerts.push({
                        type: "error",
                        message: "Please enter a valid number"
                    });
                }
            };

            mobileElem.addEventListener('focusout', handleChange);
        }

    }

    if ($(".copyToClipboard").length > 0) {
        var clipboard = new ClipboardJS('.copyToClipboard');
        clipboard.on('success', function (e) {
            window.webAlerts.push({
                type: "success",
                message: "Copy to clipboard successfully"
            });
            e.clearSelection();
        });
    }

});

$('.review-form .review-items .rates .fa').each(function () {
    var list = $(this).parent(),
        listItems = list.children(),
        itemIndex = $(this).index(),
        parentItem = list.parent();
    $(this).hover(function () {
        for (var i = 0; i < listItems.length; i++) {
            if (i <= itemIndex) {
                $(listItems[i]).addClass('hovered');
            } else {
                break;
            }
        }
        $(this).click(function () {
            for (var i = 0; i < listItems.length; i++) {
                if (i <= itemIndex) {
                    $(listItems[i]).addClass('selected');
                } else {
                    $(listItems[i]).removeClass('selected');
                }
            }
            parentItem.children('.review_stats').val(itemIndex + 1);
        });
    }, function () {
        listItems.removeClass('hovered');
    });

   

});

$(document).ready(function () {

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    // console.log(window.webAlerts);
    // for (let alertItem of window.webAlerts) {
    //     switch (alertItem.type) {
    //         case 'error':
    //             toastr.error(alertItem.message);
    //             break;
    //         case 'success':
    //             toastr.success(alertItem.message);
    //             break;
    //         case 'warning':
    //             toastr.warning(alertItem.message);
    //             break;
    //         case 'info':
    //             toastr.info(alertItem.message);
    //             break;
    //     }
    // }
});

$(document).on("click", ".form-group.password .togglePassField", function () {
    const mainFormGroup = $(this).closest(".form-group");
    const icon = mainFormGroup.find(".togglePassField i");
    const input = mainFormGroup.find("input");
    if (icon.hasClass("icofont-eye")) {
        input.attr("type", "text");
        icon.removeClass("icofont-eye").addClass("icofont-eye-blocked");
    } else {
        input.attr("type", "password");
        icon.removeClass("icofont-eye-blocked").addClass("icofont-eye");
    }
});
