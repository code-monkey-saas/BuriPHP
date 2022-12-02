<?php

namespace Controllers;

use Libraries\BuriPHP\Application;
use Libraries\BuriPHP\Controller;
use Libraries\BuriPHP\Helpers\HelperString;

class Init extends Controller
{
    public function init()
    {
        Application::setSettings(
            lang: 'es',
            timeZone: 'America/Cancun',
            locale: 'es_MX.UTF-8',
            errorReporting: 'development',
            secret: HelperString::random(64),
            useDatabase: false,
            dbType: 'MariaDB',
            dbHost: 'localhost',
            dbName: '',
            dbUser: 'root',
            dbPass: 'root',
            dbCharset: 'utf8',
            dbPrefix: '',
            dbPort: 3306
        );

        return 'Se aplicó la configuración.';
    }
}
