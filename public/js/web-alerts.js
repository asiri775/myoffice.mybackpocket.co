const WEB_ALERTS_TIMEOUT = 3000;

const WEB_ALERTS_DEFAULT_HTML = `<div class="custom-toast-bar-wrapper">
<div class="custom-toast-content"></div>
<i class="custom-close">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
        <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"></path>
    </svg>
</i>
<div class="custom-progress"></div></div>`;

const WEB_ALERTS_HTMLS = {
    success: `<i class="custom-success" style="display: flex;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"></path>
            </svg>
</i>
<div class="custom-message">
    <span class="custom-text custom-text-1">{{type}}</span>
    <span class="custom-text custom-text-2">{{messae}}</span>
</div>`,
    error: `<i class="custom-error" style="display: flex;">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
    <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"></path>
</svg>
</i>
<div class="custom-message">
<span class="custom-text custom-text-1">{{type}}</span>
<span class="custom-text custom-text-2">{{messae}}</span>
</div>`,
    info: `<i class="custom-error" style="display: flex;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
        <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"></path>
    </svg>
</i>
<div class="custom-message">
    <span class="custom-text custom-text-1">{{type}}</span>
    <span class="custom-text custom-text-2">{{messae}}</span>
</div>`,
    warning: `<i class="custom-error" style="display: flex;">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome  - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
        <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"></path>
    </svg>
</i>
<div class="custom-message">
    <span class="custom-text custom-text-1">{{type}}</span>
    <span class="custom-text custom-text-2">{{messae}}</span>
</div>`
}

console.log(window.webAlerts);

let defaultMsgs = [];
if (window.webAlerts && window.webAlerts.length > 0) {
    defaultMsgs = window.webAlerts;
}

window.webAlerts = {
    init: () => {
        var div = document.createElement('div');
        div.className = 'custom-toast-bar';
        div.id = 'webAlerts';
        document.body.appendChild(div);
        $("#webAlerts").append(WEB_ALERTS_DEFAULT_HTML);
    },
    showError: (val) => {
        let toastHtml = WEB_ALERTS_HTMLS.info;
        if (WEB_ALERTS_HTMLS.hasOwnProperty(val.type)) {
            toastHtml = WEB_ALERTS_HTMLS[val.type];
        }
        toastHtml = toastHtml.toString().replace('{{type}}', val.type).replace('{{messae}}', val.message);
        showAlerts();
        $("#webAlerts .custom-toast-content").html(toastHtml);
    },
    push: (val) => {
        window.webAlerts.showError(val);
    }
};

function showAlerts() {
    $("#webAlerts").addClass("show");
    startCloseTimer();
}

function hideAlerts() {
    $("#webAlerts").removeClass("show");
}

function startCloseTimer() {
    setTimeout(() => {
        hideAlerts();
    }, WEB_ALERTS_TIMEOUT);
}

$(document).ready(function () {
    window.webAlerts.init();
    for (let msg of defaultMsgs) {
        window.webAlerts.push(msg);
    }
});

$(document).on("click", "#webAlerts .custom-close", function () {
    hideAlerts();
    $("#webAlerts .custom-toast-content").html("");
});

setInterval(() => {
    //check login state
    if (window.IS_LOGGED_IN) {
        $.get(bookingCore.url + "/me-profile", function (r) {
            if (r.status === "loggedOut") {
                window.location.reload();
            }
            if (r.emailVerifiedAt && r.emailVerifiedAt != null) {
                if($("#verification-banner").length > 0){
                    window.location.reload();
                }
            }
        });
    }
}, 5000);