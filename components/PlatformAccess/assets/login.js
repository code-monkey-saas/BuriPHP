"use strict";

$( document ).ready(function ()
{
    $('#user-login').ajaxSubmit({
        textReDrawButton: true,
        onFatalError: function ( response )
        {
            alertify.error(response.message);
        },
        success: function ( response )
        {
            window.location.href = response.redirect;
        }
    });
});
