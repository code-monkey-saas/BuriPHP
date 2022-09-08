<?php

namespace BuriPHP;

use AppSettings;

/**
 *
 * @package BuriPHP
 *
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

class Settings extends AppSettings
{
    /**
     * Dominio o IP.
     *
     * @static
     *
     * @var string $domain
     */
    public static $domain = 'codemonkey.com.mx';

    /**
     * Idioma por default.
     * @example es, en, fr, ru..
     *
     * @static
     *
     * @var string $langDefault
     */
    public static $langDefault = 'es';

    /**
     * Zona horaria.
     *
     * @static
     *
     * @var string $timeZone
     */
    public static $timeZone = 'America/Cancun';

    /**
     * Localidad.
     *
     * @static
     *
     * @var string $locale
     */
    public static $locale = 'es_MX.UTF-8';

    /**
     * Reporte de errores.
     * @example default, none, simple, maximum, development
     *
     * @static
     *
     * @var string $errorReporting
     */
    public static $errorReporting = 'development';

    /**
     * Clave secreta.
     *
     * @static
     *
     * @var string $secret
     */
    public static $secret = 'iFfvUyP-uL]JFdFBwTGS<1?5R';

    /**
     * Estado de uso de la base de datos.
     *
     * @static
     *
     * @var boolean $useDatabase
     */
    public static $useDatabase = true;

    /**
     * Tipo de base de datos.
     * @example MySQL, MariaDB, MSSQL, PostgreSQL, Oracle, Sybase
     *
     * @static
     *
     * @var string $dbType
     */
    public static $dbType = 'mysql';

    /**
     * Dominio o IP para el servidor de base de datos.
     *
     * @static
     *
     * @var string $dbHost
     */
    public static $dbHost = 'mariadb';

    /**
     * Nombre de la base de datos
     *
     * @static
     *
     * @var string $dbName
     */
    public static $dbName = 'testing';

    /**
     * Usuario de la base de datos
     *
     * @static
     *
     * @var string $dbUser
     */
    public static $dbUser = 'admin';

    /**
     * Contraseña de la base de datos.
     *
     * @static
     *
     * @var string $dbPass
     */
    public static $dbPass = 'root';

    /**
     * Tipo de caracteres para la base de datos. Opcional.
     *
     * @static
     *
     * @var string $dbCharset
     */
    public static $dbCharset = 'utf8';

    /**
     * Prefijo para las tablas de base de datos. Opcional.
     *
     * @static
     *
     * @var string $dbPrefix
     */
    public static $dbPrefix = '';

    /**
     * Puerto de la base de datos.
     *
     * @static
     *
     * @var integer $dbPort
     */
    public static $dbPort = 3306;
}
