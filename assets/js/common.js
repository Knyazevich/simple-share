"use strict";

function Webzp_Share() {
    this.init = function() {
        this.setPopups();
        this.setShareListener();
    };
}

Webzp_Share.prototype.setPopups = function() {
    var popupBtns = document.querySelectorAll(
        '.webzp-share-block__link[data-popup="true"]'
    );
    popupBtns.forEach(function(link) {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            var href = e.target.href;
            window.open(href, "Share", "width=700, height=500");
        });
    });
};

Webzp_Share.prototype.setShareListener = function() {
    var shareButtonsBlock = document.querySelector(".webzp-share-block__list");
    var sharesCounter = document.querySelector(
        ".webzp-share-shares-tooltip-content"
    );
    shareButtonsBlock.addEventListener("click", function(e) {
        if (e.target.classList.contains("webzp-share-block__link")) {
            jQuery.ajax({
                type: "POST",
                url: "/wp-admin/admin-ajax.php",
                data: {
                    action: "webzp_share_shared"
                },
                success: function success(response) {
                    if (sharesCounter) {
                        var oldValue = parseInt(sharesCounter.innerText);
                        sharesCounter.innerText = oldValue + 1;
                    }
                }
            });
        }
    });
};

document.addEventListener("DOMContentLoaded", function() {
    var s = new Webzp_Share();
    s.init();
});
