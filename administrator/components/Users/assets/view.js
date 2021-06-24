"use strict";

$( document ).ready(function ()
{
    $.app.tableSearch();

    $('input[type="tel"]').inputmask("(999) 999-9999");

    $('[data-ajax-user]').each(function ()
    {
        let self = $(this);

        self.ajaxSubmit({
            url: 'index.php/users/read_user?id='+ self.data('ajax-user'),
            typeSend: 'click',
            method: 'GET',
            textReDrawButton: true,
            onFatalError: function ( response )
            {
                alertify.error(response.message);
            },
            success: function ( response )
            {
                let modal = $('#users_update');

                modal.find('[data-user-title]').html(response.data.username +' (#'+ response.data.id +')');
                modal.find('input[name="name"]').val(response.data.name);
                modal.find('input[name="username"]').val(response.data.username);
                modal.find('input[name="email"]').val(response.data.email);
                modal.find('select[name="prefix"]').val(response.data.prefix_phone);
                modal.find('input[name="phone"]').val(response.data.phone);
                modal.find('input[name="password"]').val('');
                modal.find('select[name="level"]').val(response.data.level);
                modal.find('input[type="checkbox"][name="permissions[]"]').prop( "checked", false );
                modal.find('form').append('<input name="id" type="hidden" value="'+ response.data.id +'"/>');

                if ( $.isArray(response.data.permissions) )
                {
                    for ( let permission of response.data.permissions )
                    {
                        modal.find('input[type="checkbox"][name="permissions[]"][value="'+ permission +'"]').prop( "checked", true );
                    }
                }

                modal.vkye_modal().open()
            }
        });
    });
});
