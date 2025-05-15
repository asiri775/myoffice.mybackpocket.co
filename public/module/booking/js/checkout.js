(function ($) {

    var isBookingConfirmBoxShowed = false;

    new Vue({
        el: '#bravo-checkout-page',
        data: {
            onSubmit: false,
            message: {
                content: '',
                type: false
            }
        },
        methods: {
            doCheckout() {
                $("#anotherConfirmationBox").html("Please wait...");
                /*if(!isBookingConfirmBoxShowed) {
                    isBookingConfirmBoxShowed = confirm('By continue, you are agree to our terms and conditions');
                }*/
                isBookingConfirmBoxShowed = true;
                // console.log(isBookingConfirmBoxShowed);
                if (isBookingConfirmBoxShowed === true) {
                    var me = this;

                    if (this.onSubmit) return false;

                    if (!this.validate()) return false;

                    this.onSubmit = true;

                    $.ajax({
                        url: bookingCore.routes.checkout,
                        data: $('.booking-form').find('input,textarea,select').serialize(),
                        method: "post",
                        success: function (res) {
                            $("#anotherConfirmationBox").html("PROCEED TO CHECKOUT");

                            if (!res.status && !res.url) {
                                me.onSubmit = false;
                            }

                            if (res.elements) {
                                for (var k in res.elements) {
                                    $(k).html(res.elements[k]);
                                }
                            }

                            if (res.message) {
                                me.message.content = res.message;
                                me.message.type = res.status;
                            }

                            if (res.url) {
                                setTimeout((e)=>{
                                    window.location.href = res.url
                                }, 1200);
                            }

                            if (res.errors && typeof res.errors == 'object') {
                                var html = '';
                                let ii = 0;
                                var errorMessage = "Please fill all required fields";
                                for (var i in res.errors) {
                                    if(ii === 0){
                                        errorMessage = res.errors[i];
                                    }
                                    ii++;
                                    html += res.errors[i] + '<br>';
                                }
                                // me.message.content = html;
                                window.webAlerts.push({
                                    type: "error",
                                    message: errorMessage
                                });
                            }

                        },
                        error: function (e) {
                            $("#anotherConfirmationBox").html("PROCEED TO CHECKOUT");
                            me.onSubmit = false;
                            var errorMessageN = "";
                            if (e.responseJSON) {
                                errorMessageN = e.responseJSON.message ? e.responseJSON.message : 'Booking cannot be completed at the moment';
                                // me.message.type = false;
 
                                let res = e.responseJSON;
                                if (res.errorCode === "loginRequired") {
                                    
                                }

                                //console.log("res", res);

                            } else {
                                if (e.responseText) {
                                    errorMessageN = e.responseText;
                                    // me.message.type = false;
                                }
                            }

                            if(errorMessageN!==""){
                                // me.message.content = errorMessageN;
                                // me.message.type = false;
                                window.webAlerts.push({
                                    type: "error",
                                    message: errorMessageN
                                });
                            }

                        }
                    });
                }
            },
            validate() {
                return true;
            }
        }
    })
})(jQuery)
