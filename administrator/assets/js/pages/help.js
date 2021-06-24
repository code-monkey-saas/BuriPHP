$( document ).ready(function ()
{
    $( document ).on('change', '.tabs', function ( event )
    {
        console.log(event.detail.tab)
        console.log('El tab cambió')
    })

    $('#gotabtwo').on('click', function ()
    {
        $('#multitabs').vkye_multitabs().goto( 'tab2' )
    })

    $( document ).on('close', '.modal', function ( event )
    {
        console.log($(this))
        console.log('Se cerró el modal')
    })

    $( document ).on('submit', '.modal', function ( event )
    {
        $(this).vkye_modal().close()
        console.log('Se envió el modal')
    })
})
