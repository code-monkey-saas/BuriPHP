"use strict";

$( document ).ready(function ()
{
    $('#users_update [button-submit]').ajaxSubmit({
        url: 'index.php/users/update_user',
        typeSend: 'click',
        formSubmit: $('form[name="users_update"]'),
        textReDrawButton: true,
        onFatalError: function ( response )
        {
            alertify.error(response.message);
        },
        success: function ( response )
        {
            swal({
                text: 'Se actualizó el usuario.',
                type: 'success',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                preConfirm: function ()
                {
                    return new Promise(function (resolve)
                    {
                        window.location.href = response.redirect;

                        setTimeout(function ()
                        {
                            resolve();
                        }, 5000);
                    });
                }
            });
        }
    });
});
