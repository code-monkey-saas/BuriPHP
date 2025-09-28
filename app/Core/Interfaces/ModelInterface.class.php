<?php

namespace Core\Interfaces;

/**
 * Interface ModelInterface
 *
 * Esta interfaz define los métodos que debe implementar un modelo en BuriPHP.
 * 
 * @package BuriPHP\Interfaces
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.1
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
interface ModelInterface
{
    /**
     * Constructor del modelo.
     * Este método se utiliza para inicializar el modelo.
     */
    public function __construct();

    /**
     * Establece un valor en una propiedad del modelo.
     * Este método permite asignar un valor a una propiedad utilizando la sintaxis de objeto.
     * 
     * @param string $name El nombre de la propiedad a establecer.
     * @param mixed $value El valor a asignar a la propiedad.
     */
    public function __set(string $name, mixed $value): void;

    /**
     * Obtiene un valor de una propiedad del modelo.
     * Este método permite acceder al valor de una propiedad utilizando la sintaxis de objeto.
     * 
     * @param string $name El nombre de la propiedad a obtener.
     * @return mixed El valor de la propiedad.
     */
    public function __get(string $name): mixed;

    /**
     * Convierte el modelo a un array.
     * Este método debe devolver una representación en forma de array del modelo.
     * 
     * @return array Un array que representa el modelo.
     */
    public function toArray(): array;
}
