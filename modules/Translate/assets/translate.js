translate = {
    change: (lang = 'es') => {
        localStorage.setItem('lang', lang)
        document.body.classList.add("disable-content")

        codemonkey.request.get(`/translate/change/i18n/${lang}`, {
            fnSuccess: () => {
                location.reload()
            }
        })
    }
}