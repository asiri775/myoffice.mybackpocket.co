$(document).on("click", '[data-toggle="tab"]', function (e) {
    let obj = $(this);
    let mainContentWrapper = obj.closest(".tab-content-wrapper");
    let target = obj.attr("href");
    mainContentWrapper.find(".tab-pane").removeClass("active");
    mainContentWrapper.find('[data-toggle="tab"]').removeClass("active");
    mainContentWrapper.find('.nav li').removeClass("active");
    mainContentWrapper.find(target).addClass("active");
    obj.addClass("active");
    obj.closest("li").addClass("active");
    e.preventDefault();
});


$(document).on("click", ".togglePassField", function () {
    var obj = $(this);
    var iconElem = obj.find("i");
    console.log(iconElem);
    var formGroup = obj.closest('.form-group');
    if (iconElem.hasClass('icofont-eye')) {
        formGroup.find('input').attr('type', 'text');
        iconElem.removeClass('icofont-eye').addClass('icofont-eye-blocked');
    } else {
        formGroup.find('input').attr('type', 'password');
        iconElem.removeClass('icofont-eye-blocked').addClass('icofont-eye');
    }
});

$(document).on("click", "#topHeadSearch form a", function () {
    $(this).closest("form").submit();
});

$(document).ready(function () {
    const successCallback = (position) => {
        let latitude = position.coords.latitude;
        let longitude = position.coords.longitude;
        $("#mapLatV").val(latitude);
        $("#mapLangV").val(longitude);
    };

    const errorCallback = (error) => {
        console.log(error);
    };
    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
    };

    navigator.geolocation.getCurrentPosition(
        successCallback,
        errorCallback,
        options
    );
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
                        // toastr.success("Phone no is valid");
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

    //nearby-spaces
    if ($(".nearby-spaces").length > 0) {
        $(".nearby-spaces").owlCarousel({
            loop: false,
            items: 3,
            margin: 30,
            responsiveClass: true,
            dots: false,
            nav: true,
            responsive: {
                0: {
                    items: 1,
                    margin: 15,
                },
                575: {
                    items: 1,
                    margin: 15,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 3,
                }
            }
        });
    }

});


$(document).on("click", ".openReviews", function () {
    const element = $('a[href="' + $(this).attr("data-href") + '"]');
    console.log(element);
    if (element != null) {
        element.click();
        document.querySelector('a[href="#module-review"]').scrollIntoView({ behavior: 'smooth' });
    }
});