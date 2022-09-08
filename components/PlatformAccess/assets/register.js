"use strict";

$( document ).ready(function ()
{
    $('input[type="tel"]').inputmask("(999) 999-9999");

    $('#user-register').ajaxSubmit({
        textReDrawButton: true,
        onFatalError: function ( response )
        {
            alertify.error(response.message);
        },
        success: function ( response )
        {
            swal({
                text: 'Bienvenido!',
                type: 'success',
                timer: 1500,
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
            }).then(function () { }, function ( dismiss )
            {
                if ( dismiss === 'timer' )
                    window.location.href = response.redirect;
            });
        }
    });
});
