/*!
 * Valkyrie v2.0.0 (https://codemonkey.com.mx/valkyrie-framework)
 * Copyright 2020 CodeMonkey Authors
 * Licensed under MIT (https://github.com/cm-valkyrie/Framework/blob/master/LICENSE)
 */

!function ( $ )
{
    "use strict"

    const Valkyrie = function () {}

    Valkyrie.prototype.dropdowns = function ()
    {
        let buttons = document.querySelectorAll(".dropmenu > button")

        for ( const button of buttons )
        {
            button.addEventListener('click', function ( event )
            {
                event.stopPropagation()

                let dropmenu = $(this).parent()

                $('.dropmenu').not(dropmenu).removeClass("active")
                dropmenu.toggleClass("active")
            })
        }
    },

    Valkyrie.prototype.modal = function ()
    {
        let btns_modals = document.querySelectorAll("[data-button-modal]")

        for ( const btn_modal of btns_modals )
        {
            btn_modal.addEventListener('click', function ( event )
            {
                let modal = $(this).data('button-modal'),
                    send_data = $(this).data('send')

                modal = $( document ).find('[data-modal="'+ modal +'"]')

                modal[0].dispatchEvent( new CustomEvent('open', {bubbles: true, detail: {data: send_data}}) )

                modal.vkye_modal().open()
            })
        }

        let btns_close = document.querySelectorAll("[data-modal] [button-close]")

        for ( const btn_close of btns_close )
        {
            btn_close.addEventListener('click', function ( event )
            {
                let modal = $(this).parents('[data-modal]')

                modal.vkye_modal().close()

                modal[0].dispatchEvent( new CustomEvent('close', {bubbles: true}) )
            })
        }

        let btns_success = document.querySelectorAll("[data-modal] [button-submit]")

        for ( const btn_success of btns_success )
        {
            btn_success.addEventListener('click', function ( event )
            {
                let modal = $(this).parents('[data-modal]')

                modal[0].dispatchEvent( new CustomEvent('submit', { bubbles: true }) )
            })
        }
    },

    Valkyrie.prototype.toggles = function ()
    {
        $( document.querySelectorAll(".toggles") ).find('.toggle.view > div').show()

        let buttons = document.querySelectorAll(".toggles > .toggle > h3")

        for ( const button of buttons )
        {
            button.addEventListener('click', function ( event )
            {
                let toggle = $( this ).parents('.toggle')
                let accordion = ( toggle.parents('.toggles').hasClass( "accordion" ) ) ? true : false

                if ( accordion === true )
                {
                    if ( !toggle.hasClass('view') ) { toggle.find('> div').slideDown(300) }
                    else { toggle.find('> div').slideUp(300) }

                    toggle.toggleClass('view')
                }
                else
                {
                    toggle.addClass("view").siblings().removeClass("view")

                    toggle.parents('.toggles').find('.toggle > div').slideUp(300)
                    toggle.find('> div').slideDown(300)
                }
            })
        }
    },

    Valkyrie.prototype.tabs = function ()
    {
        let tabs = document.querySelectorAll(".tabs")

        for ( const tab of tabs )
        {
            let buttons = tab.querySelectorAll("ul > [data-tab-target]:not([disabled])")

            $(tab).vkye_multitabs().goto( $(tab).data('tab-active') )

            for ( const button of buttons )
            {
                button.addEventListener('click', function ()
                {
                    $(tab).vkye_multitabs().goto( $(this).data('tab-target') )
                })
            }
        }
    },

    Valkyrie.prototype.test = function ()
    {
    },

    Valkyrie.prototype.init = function ()
    {
        window.addEventListener('click', function ()
        {
            $('.active').removeClass('active')
        })

        if ( $('.dropmenu').find('a:not([href])')[0] )
        {
            $('.dropmenu').find('a:not([href])')[0].addEventListener('click', function ( event )
            {
                event.stopPropagation()
            })
        }

        this.dropdowns()
        this.modal()
        this.toggles()
        this.tabs()
    }

    $.Valkyrie = new Valkyrie
    $.Valkyrie.Constructor = Valkyrie
}( window.jQuery ),

function ( $ )
{
    $.fn.vkye_modal = function ()
    {
        let self = $(this)

        return {
            open: function ()
            {
                $('html').addClass('noscroll')
                self.addClass('view').animate({ scrollTop: 0 }, 300)
            },
            close: function ()
            {
                $('html').removeClass('noscroll')
                self.removeClass('view')
            }
        }
    }

    $.fn.vkye_multitabs = function ()
    {
        let self = $(this)

        return {
            goto: function ( target = false )
            {
                if ( target == false )
                    return false

                if ( !self.find('[data-target="'+ target +'"]').length )
                    return false

                self.find('[data-tab-target="'+ target +'"]').addClass("view").siblings().removeClass("view")
                self.find('[data-target]').slideUp(300)
                self.find('[data-target="'+ target +'"]').slideDown(300)

                self[0].dispatchEvent( new CustomEvent('change', {bubbles: true, detail: {tab: target}}) )
            }
        }
    }

    $.Valkyrie.init()
}( window.jQuery )
