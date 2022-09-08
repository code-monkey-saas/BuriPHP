<?php

namespace BuriPHP\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

use \BuriPHP\System\Libraries\{Format, Errors, Session};
use \Libraries\{Functions};

class Routes
{
    private $format;
    private $path;
    private $settings_url;
    private $component;

    public function __construct($path, $settings_url, $component = null)
    {
        $this->format = new Format();
        $this->path = $path;
        $this->settings_url = $settings_url;
        $this->component = $component;
    }

    public function on_change_start()
    {
        $this->onSession();
        $this->offSession();
        $this->getRequest();
        $this->petition();
        $this->api();
        $this->permissions();
    }

    public function on_change_end()
    {
        // TODO
    }

    /**
     * Si existe una sesion y la ruta esta definida como oculta para sesiones activas,
     * redirige a la ruta principal.
     */
    private function onSession(): void
    {
        if (
            isset($this->settings_url['onSession'])
            && $this->settings_url['onSession'] === 'hidden'
            && Functions::check_session() === true
        ) {
            header('Location: /');
        }
    }

    /**
     * Si no existe una sesion y se intenta acceder a una ruta privada,
     * redirige al login con una url de referencia para cuando se haga login
     * te redirija nuevamente a la ruta que se estaba accediendo.
     */
    private function offSession(): void
    {
        if (
            isset($this->settings_url['private'])
            && $this->settings_url['private'] === true &&
            Functions::check_session() === false
        ) {
            header('Location: /login?ref=' . base64_encode($_SERVER['REQUEST_URI']));
        }
    }

    /**
     * Reune la data enviada en un request.
     */
    private function getRequest(): void
    {
        if (!empty(file_get_contents("php://input"))) {
            $_ = json_decode(file_get_contents("php://input"), true);

            if (json_last_error() != JSON_ERROR_NONE)
                parse_str(file_get_contents("php://input"), $_);

            $_REQUEST = array_merge($_REQUEST, $_);
            unset($_);
        }

        if (isset($_FILES) && !empty($_FILES)) {
            $_REQUEST = array_merge($_REQUEST, $_FILES);
        }
    }

    /**
     * Procesa lo necesario para el uso de la API
     */
    private function petition(): void
    {
        if (isset($this->settings_url['petition'])) {
            switch ($this->settings_url['petition']) {
                case 'http':
                    Format::no_ajax();
                    break;

                case 'ajax':
                    header('Content-type: application/json');
                    if (Format::exist_ajax_request() == false) die();
                    break;
            }
        }
    }

    /**
     * Procesa el REQUEST_METHOD para el uso de la API
     */
    private function api(): void
    {
        if (
            isset($this->settings_url['request'])
            && strtoupper($this->settings_url['request']) != strtoupper($_SERVER['REQUEST_METHOD'])
        ) {
            Errors::http('405');
            die();
        }

        switch (isset($this->settings_url['ContentType'])) {
            case 'JSON':
                header('Content-type: application/json');
                break;
        }
    }

    /**
     * Si existe una sesion verifica si tienes los permisos suficientes para ver la ruta.
     */
    private function permissions()
    {
        if (Functions::check_session() === true) {
            if (isset($this->settings_url['permissions']) && is_array($this->settings_url['permissions'])) {
                if (!empty(array_diff($this->settings_url['permissions'], Session::get_value('session_permissions')))) {
                    header('Location: /?http_error=403');
                }
            }
        }
    }
}
