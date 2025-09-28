<?php

namespace Core\Interfaces;

/**
 * Interface ControllerInterface
 *
 * Esta interfaz define los métodos que debe implementar un controlador en BuriPHP.
 * 
 * @package BuriPHP\Interfaces
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.1
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
interface ControllerInterface
{
    /**
     * Constructor del controlador.
     * Este método se utiliza para inicializar el controlador.
     */
    public function __construct();

    /**
     * Establece los parámetros de ruta para el controlador
     * 
     * @param array $params Parámetros de ruta a establecer
     * @return void
     */
    public function setRouteParams(array $params);

    /**
     * Obtiene los parámetros de consulta de la solicitud.
     * Este método debe devolver un array con los parámetros de consulta.
     * 
     * @return array Un array que contiene los parámetros de consulta.
     */
    public function queryParams(): array;

    /**
     * Obtiene el payload de la solicitud.
     * Este método debe devolver un array con el payload de la solicitud.
     * 
     * @return array Un array que contiene el payload de la solicitud.
     */
    public function payload(): array;
}
