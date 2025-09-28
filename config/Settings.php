<?php

namespace Config;

/**
 * Clase Settings
 * 
 * Esta clase extiende de AppSettings y se utiliza para almacenar diversas configuraciones de la aplicación.
 * 
 * Propiedades estáticas:
 * 
 * - $timeZone: Configuración de la zona horaria. Ejemplo: 'America/Mexico_City'.
 * - $locale: Configuración de la localización. Ejemplo: 'es_MX.UTF-8'.
 * - $useDatabase: true|false Para usar la conexioón a la base de datos.
 * 
 * @package Application
 * @author Kiske
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Settings
{
    public static $timeZone = 'America/Cancun';
    public static $locale = 'es_MX.UTF-8';
    public static $useDatabase = true;
}
