<?php

/**
 * @package BuriPHP.Libraries.Helpers
 * 
 * @abstract
 *
 * @since 2.0Alpha
 * @version 1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP\Helpers;

abstract class HelperSession
{
    /**
     * Inicia una sessión
     *
     * @param string|null $sessionId
     */
    public static function init($sessionId = null): void
    {
        /*  Comprobar que no haya una sesión ya iniciada */
        if (PHP_SESSION_NONE == session_status()) {
            if (!empty($sessionId)) {
                session_id($sessionId);
            }
            session_start();
            return;
        }

        if (!empty($sessionId) && $sessionId != session_id()) {
            session_destroy();
            session_id($sessionId);
            session_start();
        }
    }

    /**
     * Guarda un valor en la sesión.
     * El valor es guardado en la sesion de forma serializada.
     *
     * @param string $key
     * @param object $value
     */
    public static function setValue($key, $value = null)
    {
        $_SESSION[$key] = serialize($value);
    }

    /**
     * Devuelve un valor guardado en la sesión.
     * Si no esta definido devuelve null.
     * El valor es guardado en la sesion de forma serializada.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function getValue($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return unserialize($_SESSION[$key]);
    }


    /**
     * Devuelve un valor guardado en la sesión como un string.
     * Si no esta definido devuelve null.
     * El valor es guardado en la sesion de forma serializada
     *
     * @param string $key
     *
     * @return string
     */
    public static function getString($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        return strval(unserialize($_SESSION[$key]));
    }

    /**
     * Si no esta definido devuelve null
     * El valor es guardado en la sesion de forma serializada.
     * Si no es un valor numérico, lanza una alert.
     *
     * @param string $key
     *
     * @return int
     */
    public static function getInt($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        $tmpInt = unserialize($_SESSION[$key]);

        if (!is_numeric($tmpInt)) {
            return $tmpInt;
        }

        return intval($tmpInt);
    }

    /**
     * Devuelve un valor guardado en la sesión como un bool.
     * Si no esta definido devuelve null.
     * El valor es guardado en la sesion de forma serializada.
     * Si no es un valor bool, lanza una alert.
     *
     * @param string $key
     *
     * @return bool
     */
    public static function getBool($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        $tmpBool = unserialize($_SESSION[$key]);

        if (!is_bool($tmpBool)) {
            return $tmpBool;
        }
        return boolval($tmpBool);
    }

    /**
     *  Destruye la sesion
     */
    public static function destroy(): void
    {
        /* Comprobar si la sesión está iniciada */
        if (PHP_SESSION_ACTIVE == session_status()) {
            session_destroy();
            session_unset();
        }
    }

    /**
     * Elimina un valor guardado en la sessión
     *
     * @param string $key
     */
    public static function removeValue($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Verifica si existe una variable de sesion.
     *
     * @static
     *
     * @param string $key
     *
     * @return  void
     */
    public static function existsValue($key)
    {
        return (isset($_SESSION[$key]) && !empty($_SESSION[$key])) ? true : false;
    }

    /**
     *  Verifica si existe una sesion activa.
     */
    public static function isActive()
    {
        return (PHP_SESSION_ACTIVE == session_status()) ? true : false;
    }

    /**
     * Comprobar si la sessión esta caducada.
     * Si despues de N minutos, no ha habido una recarga, se destruye
     * la sesión.
     * Cada recarga de sessión se actualiza el tiempo.
     */
    public static function isTimeOut()
    {
        /* Comprobar que el usuario esta autenticado */
        $minutesExpiration = 30;

        // Sólo si hay caducidad y esta logeado
        if ($minutesExpiration > 0) {
            $lastActionDate = self::getValue('_LAST_ACTION_SESSION_');

            /* Existe control de tiempo */
            if (!empty($lastActionDate)) {

                // Tiempo de inactividad
                $secondsInactive = time() - $lastActionDate;

                // Segundos de timeout
                $expireAfter = $minutesExpiration * 60;

                if ($secondsInactive >= $expireAfter) {
                    // Sesión caducada
                    session_destroy();
                    // redireccionar a otra página
                    die();
                }
            }

            self::setValue('_LAST_ACTION_SESSION_', time());
        }
    }
}
