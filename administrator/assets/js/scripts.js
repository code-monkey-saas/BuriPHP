"use strict";

!function ( $ )
{
    "use strict"

    const app = function () {}

    app.prototype.onload = function ()
    {
        window.addEventListener("load", function( event )
        {
            $('#status').fadeOut()
            $('#preloader').delay(350).fadeOut('slow')
            $('body').delay(350).css({
                'overflow': 'visible'
            })
        })
    },

    app.prototype.onResize = function ()
    {
        window.addEventListener('resize', function ( e )
        {
            window.requestAnimationFrame(function ()
            {
                $.app.responsiveDropmenu()
            })
        })
    },

    app.prototype.responsiveDropmenu = function ()
    {
        if ( $( window ).width() <= 991 )
        {
            $('.dropmenu.mobile-responsive.menu-right').addClass('menu-right-none').removeClass('menu-right');
        }
        else
        {
            $('.dropmenu.mobile-responsive.menu-right-none').addClass('menu-right').removeClass('menu-right-none');
        }
    },

    app.prototype.tableSearch = function ()
    {
        $( "form[name='search'] > input[name='search']" ).keyup(function ()
        {
            let form = $(this).parents('form');
            let input = $(this);
            let filter = $.app.normalize(input[0].value.toLowerCase());
            let table = $('#'+ form.data('table-target'));
            let tds = table.find('tbody tr > td');
            let txtValue;

            table.find('tbody > tr').hide();

            for ( const td of tds )
            {
                txtValue = td.textContent || td.innerText;
                txtValue = txtValue.split(/\s+/).join(' ').trim().toLowerCase();
                txtValue = $.app.normalize(txtValue);

                if ( txtValue.indexOf(filter) > -1 )
                    $(td).parents('tr').show();
            }
        });
    },

    app.prototype.normalize = function ( str )
    {
        let from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç",
            to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
            mapping = {};

        for ( var i = 0, j = from.length; i < j; i++ )
            mapping[ from.charAt( i ) ] = to.charAt( i );

        let ret = [];
        for( var i = 0, j = str.length; i < j; i++ )
        {
            let c = str.charAt( i );
            if( mapping.hasOwnProperty( str.charAt( i ) ) )
                ret.push( mapping[ c ] );
            else
                ret.push( c );
        }
        return ret.join( '' );
    },

    app.prototype.createUrl = function ()
    {
        $( document ).on('keyup', '[data-base-url]', function ()
        {
            let self = $(this);
            let value = self.val();
            let target = self.data('base-url');

            let ajax = $(document).ajaxSubmit({
                url: 'index.php?c=System&m=get_url',
                typeSend: 'manual',
                disableButton: false,
                data: {
                    string: value
                },
                callback: function( response )
                {
                    $(target).text( response.url );
                }
            });

            ajax.send();
        });
    },

    app.prototype.uploadImagePreview = function ()
    {
        $( document ).on('change', '.upload_image_preview > input[type="file"]', function ()
        {
            let self = $(this);
            let container = self.parents('.upload_image_preview');

            container.find('.loading').remove();
            container.prepend('<div class="loading elm-stretched d-flex flex-column justify-content-center align-items-center"><div class="loading-data-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>');

            if ( self[0].files[0] )
            {
                let ajax = $(document).ajaxSubmit({
                    url: 'index.php?c=System&m=validate_image',
                    typeSend: 'manual',
                    disableButton: false,
                    data: {
                        image: self[0].files[0]
                    },
                    callback: function( response )
                    {
                        if ( response.status == 'OK' )
                        {
                            let reader = new FileReader();

                            reader.onload = function (e)
                            {
                                self[0].dispatchEvent( new CustomEvent('imageIsValid', {bubbles: true, detail: {self: self, container: container, image: e.target.result, token: response.token}}) )
                            };

                            reader.readAsDataURL(self[0].files[0]);
                        }

                        if ( response.status == 'fatal_error' )
                        {
                            alertify.error(response.message);

                            self[0].dispatchEvent( new CustomEvent('imageIsInvalid', {bubbles: true, detail: {self: self, container: container}}) )
                        }
                    }
                });

                ajax.send();
            }

            setTimeout(function ()
            {
                container.find('.loading').remove();
            }, 500);
        });

        $( document ).on('click', '.upload_image_preview > .btn[delete-elm]', function ()
        {
            let button = $(this);
            let container = button.parents('.upload_image_preview');

            swal({
                text: '¿Deseas eliminar la imágen de la galería?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#54cc96',
                cancelButtonColor: '#ff5560',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                preConfirm: function ()
                {
                    return new Promise(function (resolve)
                    {
                        container.remove();

                        setTimeout(function ()
                        {
                            resolve();
                        }, 200);
                    });
                }
            });
        });
    },

    app.prototype.editorTinymce = function ()
    {
        tinymce.init({
            selector: '[name="description"]',
            height: 400,
            plugins: [
                'advlist autolink lists link image charmap preview textcolor searchreplace visualblocks code fullscreen insertdatetime media table paste code help autosave hr'
            ],
            menu: {
                edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
                insert: {title: 'Insert', items: 'link image media | hr | insertdatetime'},
                view: {title: 'View', items: 'visualblocks visualaid'},
                format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
                table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
                tools: {title: 'Tools', items: 'searchreplace charmap code help'}
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

    app.prototype.init = function ()
    {
        this.onload()
        this.onResize()
        this.responsiveDropmenu()

        $( document ).on('click', 'li.menu-item > #trigger-nav-mobile', function ( event )
        {
            event.stopPropagation()

            $(this).find('> .hamburger-menu').toggleClass('animate');
            $('nav.navbar-custom').toggleClass('active')
        })

        $( document ).on('click', 'nav.navbar-custom', function ( event )
        {
            if ( $(window).width() < 767 )
                event.stopPropagation()
        })
    }

    $.app = new app
    $.app.Constructor = app
}( window.jQuery ),

function ( $ )
{
    $.app.init()
}( window.jQuery )
