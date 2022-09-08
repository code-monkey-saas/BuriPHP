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

abstract class HelperArray
{

    /**
     * Ordena un array por los valores de forma natural. 
     * Se pierden las claves.
     * Devuelve un array con los valores ordenados
     *
     * @param array $arr
     * @param bool  $ascendent
     *
     * @return array
     */
    public static function sortNatural($arr, $ascendent = true)
    {
        $array_tmp = HelperConvert::toArray($arr);

        usort($array_tmp, "strnatcmp");

        if (!$ascendent) {
            $array_tmp = array_reverse($array_tmp);
        }

        return $array_tmp;
    }

    /**
     * Devuelve un array ordendo por las columna 
     * o columnas que queramos
     * @return mixed
     */
    function sortMultiValue()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('sortMultiValue', $args);
        return array_pop($args);
    }


    /**
     * Ordena los valores de un array, se mantienen las claves.
     * Devuelve un array ordenado.
     *
     * @param array $arr
     * @param bool  $ascendent
     *
     * @return array
     */
    public static function sort($arr, $ascendent = true)
    {
        $arrayTmp = HelperConvert::toArray($arr);
        if ($ascendent) {
            asort($arrayTmp);
        } else {
            arsort($arrayTmp);
        }
        return $arrayTmp;
    }

    /**
     * Elimina el primer valor de una instancia del array.
     * Devuelve un array sin el valor eliminado.
     * Si algún valor esta vacío o no es un array, 
     * este se convierte a un array.
     *
     * @param array $arr
     * @param       $val
     *
     * @return array
     */
    public static function removeValueFirst($arr, $val)
    {
        $arrayTmp  = [];
        $found = false;

        foreach (HelperConvert::toArray($arr) as $key => $value) {

            if ($value == $val && !$found) {
                $found = true;
            } else {
                $arrayTmp[$key] = $value;
            }
        }
        return $arrayTmp;
    }

    /**
     * Elimina todos los valores del array de una instancia.
     * Devuelve un array sin los valores eliminados.
     *
     * @param array $arr
     * @param       $sStr
     *
     * @return array
     */
    public static function removeValueAll($arr, $sStr)
    {
        $arrayTmp = [];

        foreach (HelperConvert::toArray($arr) as $key => $value) {

            if ($value != $sStr) {
                $arrayTmp[$key] = $value;
            }
        }
        return $arrayTmp;
    }

    /**
     * Elimina una posición determinada del array.
     * Devuelve una array sin el valor eliminado.
     * Si algún valor esta vacío o no es un array, 
     * este se convierte a un array.
     *
     * @param array $arr
     * @param int   $pos
     *
     * @return array
     */
    public static function removePos($arr, $pos)
    {
        $n = 0;

        $arrayTmp = [];
        foreach (HelperConvert::toArray($arr) as $key => $value) {
            if ($pos != $n) {
                $arrayTmp[$key] = $value;
            }
            $n++;
        }
        return $arrayTmp;
    }

    /**
     * Devuelve un array sin el último valor del array.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function removeLast($arr)
    {
        $arr = HelperConvert::toArray($arr);
        array_pop($arr);
        return $arr;
    }

    /**
     * Devuelve un array donde se ha eliminado una clave y su valor.
     * El array original no se modifica.
     * Si algún valor esta vacío o no es un array,
     * este se convierte a un array.
     * Si la cale no se encuentra, devuelve el mismo array
     *
     * @param $arr
     * @param $deleteKey
     *
     * @return array
     */
    public static function removeKey($arr, $deleteKey)
    {
        $arrayTmp  = [];
        $found = false;
        foreach (HelperConvert::toArray($arr) as $key => $valor) {
            if ($key == $deleteKey && !$found) {
                $found = true;
            } else {
                $arrayTmp[$key] = $valor;
            }
        }
        return $arrayTmp;
    }

    /**
     * Devuelve y elimina el primer valor del array
     *
     * @param array $arr
     *
     * @return mixed
     */
    public static function removeFirst(&$arr)
    {
        return array_shift($arr);
    }

    /**
     * Devuelve un array sin duplicados.
     * Si algún valor esta vacío o no es un array,
     * este se convierte a un array.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function removeDuplicates($arr)
    {
        return array_unique(HelperConvert::toArray($arr));
    }

    /**
     * Transforma un array en un string separados por un separador.
     * Por defecto el separador es la coma (,).
     *
     * @param array  $arr
     * @param string $separador
     *
     * @return string
     */
    public static function joinValues($arr, $separador = ',')
    {
        return join($separador, HelperConvert::toArray($arr));
    }


    /**
     * Devuelve la posición que ocupa un valor de un array 
     * o -1 si no existe.
     * Si algún valor esta vacío o no es un array, 
     * este se convierte a un array.
     *
     * @param array $arr
     * @param mixed $value
     *
     * @return int
     */
    public static function indexOfValue($arr, $value)
    {
        $arr = HelperConvert::toArray($arr);
        if (count($arr) == 0) {
            return -1;
        }

        // Como la función es sensitiva a mayúsculas i minúsculas
        // hacemos una copia de array poniendo 
        // las claves en minúsculas
        // la comparación se hace en minúsculas.
        $arrayCopy = array_map(function ($val) {
            return HelperString::toLower($val);
        }, array_values($arr));

        $pos = array_search($value, $arrayCopy);
        if (false === $pos) {
            return -1;
        }
        return $pos;
    }

    /**
     * Devuelve la posición que ocupa una clave o -1 
     * si no la encuentra.
     * Si algún valor esta vacío o no es un array, 
     * este se convierte a un array.
     *
     * @param array $arr
     * @param mixed $key
     *
     * @return int
     */
    public static function indexOfKey($arr, $key)
    {
        $n = 0;
        foreach (HelperConvert::toArray($arr) as $key_tmp => $value) {
            if (HelperValidate::areEquals($key_tmp, $key)) {
                return $n;
            }
            $n++;
        }
        return -1;
    }

    /**
     * Devuelve el valor de la posición secuencial determinada 
     * o un valor por defecto so no existe.
     * Si algún valor esta vacío o no es un array, 
     * este se convierte a un array.
     *
     * @param array  $arr
     * @param int    $pos
     * @param string $default
     *
     * @return mixed
     */
    public static function getValueByPos($arr, $pos, $default = '')
    {
        $n = 0;
        foreach (HelperConvert::toArray($arr) as $value) {
            if ($n == $pos) {
                return $value;
            }
            $n++;
        }
        return $default;
    }

    /**
     * Devuelve el valor de una clave.
     * Si el parámetro no es un array, se convierte a array.
     * La clave es independiente de maýusculas, minúsculas y acentos.
     *
     * @param array $arr
     * @param mixed $key
     *
     * @return mixed
     */
    public static function getValueByKey($arr, $key)
    {
        $arrTmp = HelperConvert::toArray($arr);

        $key = HelperString::toLower(HelperString::removeAccents($key));

        // Como la función array_key_exists es 
        // sensitiva a mayúsculas i minúsculas
        // hacemos una copia de array poniendo las 
        // claves en minúsculas
        // la comparación se hace en minúsculas.
        $arrayCopy = array_map(function ($val) {
            $val = HelperString::removeAccents($val);
            return HelperString::toLower($val);
        }, array_keys($arrTmp));

        /* Si existe, tenemos su posición dentro del array */
        $pos = array_search($key, $arrayCopy);
        if ($pos === false) {
            return null;
        }

        /* Obtenemos un array de un sólo valor, que es 
        el que nos interesa */
        $arrSingle = array_slice($arrTmp, $pos, 1);

        /* Devolvemos su valor */
        return $arrSingle[array_keys($arrSingle)[0]];
    }

    /**
     * Devuelve una parte del array entre dos posiciones.
     * Si algún valor esta vacío o no es un array, 
     * este se convierte a un array.
     *
     * @param array $arr
     * @param int   $pos_inicio
     * @param int   $pos_fin
     *
     * @return array
     */
    public static function getSubArray($arr, $pos_inicio, $pos_fin)
    {
        return array_slice(
            HelperConvert::toArray($arr),
            $pos_inicio,
            $pos_fin
        );
    }

    /**
     * Devuelve el número de niveles de un array. Función recursiva.
     * Si el parámetro no es un array, devuelve cero.
     *
     * @param array $array
     * @param int   $niveles
     * @param array $current_array
     *
     * @return int
     */
    public static function getLevels($array, $niveles = -1, $current_array = [])
    {
        $niveles++;
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $current_array[] = self::getLevels($value, $niveles);
            }
        } else {
            return $niveles;
        }
        foreach ($current_array as $value) {
            $niveles = $value > $niveles ? $value : $niveles;
        }
        return $niveles;
    }

    /**
     * Devuelve y elimina el último valor del array.
     * El array pasado al la función queda modificado.
     *
     * @param array $arr
     *
     * @return mixed
     */
    public static function getLastAndRemove(&$arr)
    {
        $arr = HelperConvert::toArray($arr);
        return array_pop($arr);
    }

    /**
     * Devuelve el último valor del array.
     * El array pasado al la función queda modificado.
     *
     * @param array $arr
     *
     * @return mixed
     */
    public static function getLast($arr)
    {
        $arr = HelperConvert::toArray($arr);
        return array_pop($arr);
    }

    /**
     * Devuelve un array con todas las claves.
     * Si es null o no es un array, devuelve un array vacío.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function getKeys($arr)
    {
        // Si no es un array, devolvemos un array vacío
        if (!is_array($arr)) {
            return [];
        }
        return array_keys($arr);
    }

    /**
     * Indica si un valor existe en un array.
     * Se comprueba que al parámetro sea un array.
     * Es independiente de maýusculas y minúsculas pero no acentuadas
     *
     * @param array $arr
     * @param mixed $value
     *
     * @return bool
     */
    public static function existsValue($arr, $value)
    {
        // Si no es un array, devolvemos false (No existe)
        if (!is_array($arr)) {
            return false;
        }

        $value = HelperString::toLower($value);

        // Como la función es sensitiva a mayúsculas i minúsculas
        // hacemos una copia de array poniendo 
        // las claves en minúsculas
        // la comparación se hace en minúsculas.
        $arrayCopy = array_map(function ($val) {
            return HelperString::toLower($val);
        }, $arr);

        return !(false === array_search($value, $arrayCopy));
    }

    /**
     * Indica si una clave existe en un array.
     * Se comprueba que al parámetro sea un array.
     * Es independiente de maýusculas, minúsculas y acentuadas
     *
     * @param array $arr
     * @param mixed $key
     *
     * @return bool
     */
    public static function existsKey($arr, $key)
    {
        // Si no es un array, devolvemos false (No existe)
        if (!is_array($arr)) {
            return false;
        }
        $key = HelperString::removeAccents($key);
        $key = HelperString::toLower($key);

        // Como la función array_key_exists es sensitiva 
        // a mayúsculas i minúsculas
        // hacemos una copia de array poniendo las claves 
        // en minúsculas
        // la comparación se hace en minúsculas.
        $arrayCopy = array_map(function ($val) {
            $val = HelperString::removeAccents($val);

            return HelperString::toLower($val);
        }, array_keys($arr));

        return (array_search($key, $arrayCopy) > 0);
    }

    /**
     * Devuelve todos los claves/valores del array 1 que NO están 
     * en las claves del otro array.
     * Si algún valor esta vacío o no es un array, este se 
     * convierte a un array.
     *
     * @param $arr1
     * @param $arr2
     *
     * @return array
     */
    public static function dif($arr1, $arr2)
    {
        return array_diff(
            HelperConvert::toArray($arr1),
            HelperConvert::toArray($arr2)
        );
    }

    /**
     * Elimina toda una columna por si posición de un array. 
     * (0 es la primera)
     *
     * @param $array
     * @param $position
     *
     * @return mixed
     */
    public static function deleteColumnByPosition($array, $position)
    {
        array_walk(
            $array,
            function (&$arr) use ($position) {
                array_splice($arr, $position, 1);
            }
        );

        return $array;
    }

    /**
     * Devuelve un array sin valores vacíos (null, '').
     * Si el valor está vació, de vuelve un array vació, y si no 
     * es  un array, lo convierte a un array.
     *
     * @param $arr
     *
     * @return array
     */
    public static function compact($arr)
    {
        if (HelperValidate::isEmpty($arr)) {
            return [];
        }

        $arrayTmp = [];
        foreach (HelperConvert::toArray($arr) as $key => $value) {
            if (!HelperValidate::isEmpty($arr[$key])) {
                $arrayTmp[] = $value;
            }
        }
        return $arrayTmp;
    }

    /**
     * Combina los valores del primer array como las claves con los
     * valores del segundo array.
     * Si algún valor esta vacío o no es un array, este se convierte 
     * a un array.
     *
     * @param array $arrayKeys
     * @param array $arrayValues
     *
     * @return array
     */
    public static function combineKeysValue($arrayKeys, $arrayValues)
    {
        return array_combine(
            HelperConvert::toArray($arrayKeys),
            HelperConvert::toArray($arrayValues)
        );
    }

    /**
     * Combina dos array.
     * Devuelve un array.
     *
     * @param array $arr1
     * @param array $arr2
     *
     * @return array
     */
    public static function combine($arr1, $arr2)
    {
        return array_merge(
            HelperConvert::toArray($arr1),
            HelperConvert::toArray($arr2)
        );
    }

    /**
     * Inserta una valor en una posición determianda de un array.
     * La primera posición es la 0 y ha de ser numérico.
     * Los indices no tienen porque ser numéricos
     *
     * @param array $arr
     * @param int   $pos
     * @param mixed $value
     *
     * @return array
     */
    public static function pushToPos($arr, $pos, $value)
    {
        array_splice($arr, $pos, 0, [$value]);
        return $arr;
    }

    /**
     * Añade un valor al inicio del array.
     * Devuelve un nuevo array con el valor insertado, no modifica 
     * el original.
     *
     * @param array $arr
     * @param mixed $value
     *
     * @return array
     */
    public static function prepend($arr, $value)
    {
        // Nos aseguramos de que sea un array
        $arrTmp = HelperConvert::toArray($arr);

        // Añadimos en la primnera posición
        array_unshift($arrTmp, $value);

        return $arrTmp;
    }

    /**
     * Añade un  valor al final del array.
     * Devuelve un nuevo array con el valor insertado, no modifica 
     * el original.
     *
     * @param array $arr
     * @param mixed $value
     *
     * @return array
     */
    public static function append($arr, $value)
    {
        // Nos aseguramos de que sea un array
        $arrTmp = HelperConvert::toArray($arr);

        // Añadimos en la última posición
        array_push($arrTmp, $value);

        return $arrTmp;
    }

    /**
     * Deveulve sólo los valores del array1 que estan en los valores 
     * del otro array.
     * No devuelve ni compara claves.
     * Si algún valor esta vacío o no es un array, este se convierte a 
     * un array.
     *
     * @param array $arr1
     * @param array $arr2
     *
     * @return array
     */
    public static function intersectionByValue($arr1, $arr2)
    {
        return array_intersect(
            HelperConvert::toArray($arr1),
            HelperConvert::toArray($arr2)
        );
    }

    /**
     * Devuelve sólo las claves/valores del array1 que están en las
     * claves/valores del otro array.
     * Si algún valor esta vacío o no es un array, este se convierte a
     * un array.
     *
     * @param array $arr1
     * @param array $arr2
     *
     * @return array
     */
    public static function intersectionByKeyValue($arr1, $arr2)
    {
        return array_intersect_assoc(
            HelperConvert::toArray($arr1),
            HelperConvert::toArray($arr2)
        );
    }

    /**
     * Deveulve sólo las claves/valores del array1 que están en las 
     * claves del otro array.
     * No compaa en los valores del seguno array.
     * Si algún valor esta vacío o no es un array, este se convierte a 
     * un array.
     *
     * @param array $arr1
     * @param array $arr2
     *
     * @return array
     */
    public static function intersectionByKey($arr1, $arr2)
    {
        return array_intersect_key(
            HelperConvert::toArray($arr1),
            HelperConvert::toArray($arr2)
        );
    }
}
