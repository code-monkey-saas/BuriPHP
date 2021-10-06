"use strict";

!function ($) {
    "use strict"

    const app = function () { }

    app.prototype.onload = function () {
        window.addEventListener("load", function (event) {
            $('#status').fadeOut()
            $('#preloader').delay(350).fadeOut('slow')
            $('body').delay(350).css({
                'overflow': 'visible'
            })
        })
    },

    app.prototype.onResize = function () {
        window.addEventListener('resize', function (e) {
            window.requestAnimationFrame(function () {
                $.app.responsiveDropmenu()
                $.app.fullscreenModals()
            })
        })
    },

    app.prototype.responsiveDropmenu = function () {
        if ($(window).width() <= 991) {
            $('.dropmenu.mobile-responsive.menu-right').addClass('menu-right-none').removeClass('menu-right');
        }
        else {
            $('.dropmenu.mobile-responsive.menu-right-none').addClass('menu-right').removeClass('menu-right-none');
        }
    },

    app.prototype.tableSearch = function () {
        $("form[name='search'] > input[name='search']").keyup(function () {
            let form = $(this).parents('form');
            let input = $(this);
            let filter = $.app.normalize(input[0].value.toLowerCase());
            let table = $('#' + form.data('table-target'));
            let tds = table.find('tbody tr > td');
            let txtValue;

            table.find('tbody > tr').hide();

            for (const td of tds) {
                txtValue = td.textContent || td.innerText;
                txtValue = txtValue.split(/\s+/).join(' ').trim().toLowerCase();
                txtValue = $.app.normalize(txtValue);

                if (txtValue.indexOf(filter) > -1)
                    $(td).parents('tr').show();
            }
        });
    },

    app.prototype.normalize = function (str) {
        let from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç",
            to = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
            mapping = {};

        for (var i = 0, j = from.length; i < j; i++)
            mapping[from.charAt(i)] = to.charAt(i);

        let ret = [];
        for (var i = 0, j = str.length; i < j; i++) {
            let c = str.charAt(i);
            if (mapping.hasOwnProperty(str.charAt(i)))
                ret.push(mapping[c]);
            else
                ret.push(c);
        }
        return ret.join('');
    },

    app.prototype.uploadImagePreview = function () {
        $(document).on('change', '.upload_image_preview > input[type="file"]', function () {
            let self = $(this);
            let container = self.parents('.upload_image_preview');

            container.find('.loading').remove();
            container.prepend('<div class="loading elm-stretched d-flex flex-column justify-content-center align-items-center"><div class="loading-data-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>');

            if (self[0].files[0]) {
                let ajax = $(document).ajaxSubmit({
                    url: '?validate=image',
                    typeSend: 'manual',
                    disableButton: false,
                    data: {
                        image: self[0].files[0]
                    },
                    onFatalError: function (response) {
                        alertify.error(response.message);

                        self[0].dispatchEvent(new CustomEvent('imageIsInvalid', { bubbles: true, detail: { self: self, container: container } }))
                    },
                    success: function (response) {
                        let reader = new FileReader();

                        reader.onload = function (e) {
                            self[0].dispatchEvent(new CustomEvent('imageIsValid', { bubbles: true, detail: { self: self, container: container, image: e.target.result, token: response.token } }))
                        };

                        reader.readAsDataURL(self[0].files[0]);
                    }
                });

                ajax.send();
            }
            else {
                let image = $('<figure/>', { class: 'm-0' }).append(
                    $('<img/>', { class: 'img-fluid', src: container.data('image-default') })
                );

                container.find('> figure').remove();
                container.prepend(image);
                self.val('');
            }

            setTimeout(function () {
                container.find('.loading').remove();
            }, 500);
        });
    },

    app.prototype.editorTinymce = function (selector = "") {
        tinymce.init({
            selector: selector,
            height: 400,
            plugins: [
                'advlist autolink lists link image charmap preview textcolor searchreplace visualblocks code fullscreen insertdatetime media table paste code help hr'
            ],
            menu: {
                edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall' },
                insert: { title: 'Insert', items: 'link image media | hr | insertdatetime' },
                view: { title: 'View', items: 'visualblocks visualaid' },
                format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat' },
                table: { title: 'Table', items: 'inserttable tableprops deletetable | cell row column' },
                tools: { title: 'Tools', items: 'searchreplace charmap code help' }
            },
            toolbar: [
                'restoredraft undo redo | cut copy paste | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image link | code fullscreen'
            ],
            mobile: {
                theme: 'mobile',
            },

            language: 'es_MX',
            branding: false,
            advlist_number_styles: "default lower-alpha lower-roman upper-alpha upper-roman",
            link_context_toolbar: true,
            link_assume_external_targets: true,
            image_caption: true,
            image_advtab: true,
            image_title: true,
            insertdatetime_formats: ["%H:%M:%S", "%d-%m-%Y", "%I:%M:%S %p", "%D"],
            insertdatetime_element: true
        });
    },

    app.prototype.fullscreenModals = function () {
        if ($(window).width() <= 991) $('[data-modal]:not(.not-resize)').addClass('fullscreen')
        else $('[data-modal]').removeClass('fullscreen')
    },

    app.prototype.checkNumbersInput = function (e) {
        let key = (document.all) ? e.keyCode : e.which;

        if (key == 8) {
            return true;
        }

        let patron = /[.0-9]/;
        let end_key = String.fromCharCode(key);
        return patron.test(end_key);
    },

    app.prototype.onlyNumbers = function ( e ) {
        let string = e.target.value.replace(/[^.0-9]/g, '')

        $(e.target).val(string)
    },

    app.prototype.removeElementTarget = function (elm = null) {
        if (elm != null) {
            $(elm).remove();
        }
    },

    app.prototype.addButtonsAction = function (obj = null) {
        let html = $('.navigation-actions');
        for (let [key, value] of Object.entries(obj)) {
            
            let li = $('<li/>', {class: 'm-l-5'});

            switch (key) {
                case 'dropdown':
                    let dropmenu = $('<div/>', { class: 'dropmenu menu-right' });
                    let submenu = $('<div/>', { class: 'dropdown' });
                    let callButton = $('<button/>', { class: 'btn waves-effect waves-light', text: value.label });
                    let icon = $('<i/>', { class: 'fa fa-caret-down m-l-5' });

                    for (let _ of Object.entries(value.dropdown))
                    {
                        submenu.append($('<a/>', _[1]))
                    }

                    callButton.append(icon);
                    dropmenu.append(callButton, submenu);

                    li.append(dropmenu);
                    break;

                case 'button':
                    let button = $('<a/>', value);
                    li.append(button);
                    break;
            }

            html.append(li);
        }
    },

    app.prototype.init = function () {
        this.onload()
        this.onResize()
        this.responsiveDropmenu()
        this.fullscreenModals()

        $(document).on('click', 'li.menu-item > #trigger-nav-mobile', function (event) {
            event.stopPropagation()

            $(this).find('> .hamburger-menu').toggleClass('animate');
            $('body').toggleClass('open-sidebar-menu')
        })

        $(document).on('click', 'nav.navbar-custom', function (event) {
            if ($(window).width() < 767)
                event.stopPropagation()
        })
    }

    $.app = new app
    $.app.Constructor = app
}(window.jQuery),

function ($) {
    $.app.init()
}(window.jQuery)
