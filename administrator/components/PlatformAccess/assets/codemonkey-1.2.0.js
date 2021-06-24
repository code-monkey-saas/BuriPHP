/**
* @name ajaxSubmit
* @description Envia un formulario via Ajax
* @param settings object Modifica los parametros del Ajax y ofrece un callback cuando la respuesta es un HTTP 200
*
* @return null
*/
$.fn.ajaxSubmit = function ( userSettings = {} )
{
    let element = $(this)
    const ajax = {}
    const settings = {
        ajaxUrl: '',
        ajaxMethod: 'post',
        ajaxContentType: false,
        ajaxProcessData: false,
        ajaxCache: false,
        ajaxDataType: 'json',
        data: {},
        typeSend: 'form',
        formSubmit: $(this),
        buttonSubmit: $(this).find('[type="submit"]'),
        disableButton: true,
        textDefaultLoading: $(this).find('[type="submit"]').html(),
        textLoading: 'Cargando...',
        textReDrawButton: false
    }

    if ( typeof userSettings.url !== 'undefined' ) settings.ajaxUrl = userSettings.url
    if ( typeof userSettings.method !== 'undefined' ) settings.ajaxMethod = userSettings.method
    if ( typeof userSettings.contentType !== 'undefined' ) settings.ajaxContentType = userSettings.contentType
    if ( typeof userSettings.processData !== 'undefined' ) settings.ajaxProcessData = userSettings.processData
    if ( typeof userSettings.cache !== 'undefined' ) settings.ajaxCache = userSettings.cache
    if ( typeof userSettings.dataType !== 'undefined' ) settings.ajaxDataType = userSettings.dataType
    if ( typeof userSettings.data !== 'undefined' ) settings.data = userSettings.data
    if ( typeof userSettings.typeSend !== 'undefined' ) settings.typeSend = userSettings.typeSend
    if ( typeof userSettings.formSubmit !== 'undefined' ) settings.formSubmit = userSettings.formSubmit
    if ( typeof userSettings.buttonSubmit !== 'undefined' ) settings.buttonSubmit = userSettings.buttonSubmit
    if ( typeof userSettings.disableButton !== 'undefined' ) settings.disableButton = userSettings.disableButton
    if ( typeof userSettings.textDefaultLoading !== 'undefined' ) settings.textDefaultLoading = userSettings.textDefaultLoading
    if ( typeof userSettings.textLoading !== 'undefined' ) settings.textLoading = userSettings.textLoading
    if ( typeof userSettings.textReDrawButton !== 'undefined' ) settings.textReDrawButton = userSettings.textReDrawButton

    switch ( settings.typeSend )
    {
        case 'manual': break

        case 'form': default:
            element[0].addEventListener('submit', function ( event )
            {
                event.preventDefault()

                ajax.send( new FormData( settings.formSubmit[0] ) )
            })
            break

        case 'change':
            element[0].addEventListener('change', function ( event )
            {
                event.preventDefault()

                ajax.send()
            })
            break

        case 'click':
            settings.buttonSubmit = element
            settings.textDefaultLoading = element.html()

            element[0].addEventListener('click', function ()
            {
                if ( typeof userSettings.formSubmit !== 'undefined' )
                    ajax.send( new FormData(settings.formSubmit[0]) )
                else
                    ajax.send()
            })
            break
    }

    ajax.send = function ( data = new FormData() )
    {
        if ( typeof settings.data === 'object' )
        {
            for ( let [key, value] of Object.entries(settings.data) )
            {
                data.append(key, value)
            }
        }

        $.ajax({
            data: data,
            url: settings.ajaxUrl,
            type: settings.ajaxMethod,
            contentType: settings.ajaxContentType,
            processData: settings.ajaxProcessData,
            cache: settings.ajaxCache,
            dataType: settings.ajaxDataType,
            beforeSend: function ()
            {
                if ( settings.disableButton === true )
                {
                    settings.buttonSubmit.attr('disabled', 'disabled')
                    settings.buttonSubmit.html(settings.textLoading)
                }

                settings.formSubmit.find('label.error').removeClass('error')
                settings.formSubmit.find('p.error').remove()
            },
            error: function (request, error)
            {
                settings.buttonSubmit.removeAttr('disabled')
                settings.buttonSubmit.html(settings.textDefaultLoading)
            },
            success: function ( response, status, xhr )
            {
                if ( response.status == 'error' )
                {
                    settings.buttonSubmit.removeAttr('disabled')
                    settings.buttonSubmit.html(settings.textDefaultLoading)

                    if ( typeof response.labels !== 'undefined' )
                    {
                        for ( let label of response.labels )
                        {
                            if ( label[1].length > 0 )
                            {
                                settings.formSubmit.find('[name="' + label[0] + '"]').parents('label').addClass('error').append('<p class="error">' + label[1] + '</p>')
                            }
                            else
                            {
                                settings.formSubmit.find('[name="' + label[0] + '"]').parents('label').addClass('error')
                            }
                        }

                        settings.formSubmit.find('label.error [name]')[0].focus()
                    }

                    if ( typeof userSettings.onError === 'function' ) userSettings.onError( response, status, xhr )
                }
                else if ( response.status == 'fatal_error' )
                {
                    settings.buttonSubmit.removeAttr('disabled')
                    settings.buttonSubmit.html(settings.textDefaultLoading)

                    if ( typeof userSettings.onFatalError === 'function' ) userSettings.onFatalError( response, status, xhr )
                }
                else
                {
                    if ( typeof userSettings.success === 'function' ) userSettings.success( response, status, xhr )

                    if ( settings.textReDrawButton == true )
                    {
                        settings.buttonSubmit.removeAttr('disabled')
                        settings.buttonSubmit.html(settings.textDefaultLoading)
                    }
                }
            }
        })
    }

    return ajax
}

/**
* @name onScroll
* @description Agrega y quita una clase, despues del tamaño asignado al hacer scroll
* @param height tamaño en pixeles para realizar la accion
* @param cls clase a usar
*
* @return null
*/
$.fn.onScroll = function ( height = 100, cls = 'onscroll' )
{
    window.addEventListener('scroll', function ( e )
    {
        window.requestAnimationFrame(function ()
        {
            if ( window.scrollY > height )
                $('html').addClass(cls)

            if ( window.scrollY <= height )
                $('html').removeClass(cls)
        })
    })
}
