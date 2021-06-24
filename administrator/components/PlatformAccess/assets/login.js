"use strict";

$( document ).ready(function ()
{
    $('form[name="login"]').ajaxSubmit({
        textReDrawButton: true,
        onFatalError: function ( response )
        {
            alertify.error(response.message);
        },
        success: function ( response )
        {
            location.reload()
        }
    });
});
