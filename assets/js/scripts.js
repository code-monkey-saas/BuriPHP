"use strict";

!function ($) {
    "use strict"

    const app = function () { }

    app.prototype.onload = function () {
        window.addEventListener("load", function (event) {
        })
    }

    app.prototype.onResize = function () {
        window.addEventListener('resize', function (e) {
            window.requestAnimationFrame(function () {
            })
        })
    }

    app.prototype.URLSearchParams = function (param = null) {
        const urlParams = new URLSearchParams(window.location.search)
        return urlParams.get(param)
    }

    app.prototype.checkNumbersInput = function (e) {
        let key = (document.all) ? e.keyCode : e.which;

        if (key == 8) {
            return true;
        }

        let patron = /[.0-9]/;
        let end_key = String.fromCharCode(key);
        return patron.test(end_key);
    }

    app.prototype.fullscreenModals = function () {
        if ($(window).width() <= 991) $('[data-modal]:not(.not-resize)').addClass('fullscreen')
        else $('[data-modal]').removeClass('fullscreen')
    }

    app.prototype.responsiveDropmenu = function () {
        if ($(window).width() <= 991) {
            $('.dropmenu.mobile-responsive.menu-right').addClass('menu-right-none').removeClass('menu-right');
        }
        else {
            $('.dropmenu.mobile-responsive.menu-right-none').addClass('menu-right').removeClass('menu-right-none');
        }
    }

    app.prototype.init = function () {
        $('header.main-header .topbar-navigation nav.main-menu li.list-inline-item:has( > .megamenu),header.main-header .topbar-navigation nav.main-menu li.list-inline-item:has( > .submenu)').addClass('angle-down');

        $(document).on('click', '#trigger-nav-mobile', function (event) {
            event.stopPropagation()

            $(this).find('> .hamburger-menu').toggleClass('animate');
            $('body').toggleClass('mobile-menu-open');
        });

        if (this.URLSearchParams('http_error') == 403) {
            // Show error 403 permissions.
            console.log('Insufficient permits');
        }

        this.onResize()
    }

    $.app = new app
    $.app.Constructor = app
}(window.jQuery), function ($) {
    $.app.init()
}(window.jQuery)
