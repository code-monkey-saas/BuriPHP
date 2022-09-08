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

use BuriPHP\Settings;

abstract class HelperCrypt
{
    /**
     * Encriptación por trasposición
     *
     * @param string $str
     *
     * @return string
     */
    public static function encryptTransposition($str)
    {
        $len  = strlen($str);

        $strEncrypt = "";

        for ($position = 0; $position < $len; $position++) {

            $key_to_use = (($len + $position) + 1);
            $key_to_use = (255 + $key_to_use) % 255;

            $byte_to_be_encrypted = substr($str, $position, 1);

            $ascii_num_byte_to_encrypt = ord($byte_to_be_encrypted);

            $xored_byte = $ascii_num_byte_to_encrypt ^ $key_to_use;

            $encrypted_byte = chr($xored_byte);

            $strEncrypt .= $encrypted_byte;
        }

        return base64_encode($strEncrypt);
    }

    /**
     * Desencriptación por trasposición
     *
     * @param string $strEncriptado
     *
     * @return string
     */
    public static function decryptTransposition($strEncriptado)
    {
        $strEncriptado = base64_decode($strEncriptado);

        $len = strlen($strEncriptado);

        $str = "";

        for ($position = 0; $position < $len; $position++) {

            $key_to_use = (($len + $position) + 1);
            $key_to_use = (255 + $key_to_use) % 255;

            $byte_to_be_encrypted = substr($strEncriptado, $position, 1);

            $ascii_num_byte_to_encrypt = ord($byte_to_be_encrypted);

            $xored_byte = $ascii_num_byte_to_encrypt ^ $key_to_use;

            $encrypted_byte = chr($xored_byte);

            $str .= $encrypted_byte;
        }
        return $str;
    }

    /**
     * Encripta un texto mediante una contraseña
     *
     * @param $message
     * @param $key
     *
     * @return string
     */
    public static function encryptWithKey($message, $key)
    {
        $encryptedText = "";
        settype($message, "string");
        $i = strlen($message) - 1;
        $j = strlen($key);
        if (strlen($message) <= 0) {
            return "";
        }
        do {
            $encryptedText .= ($message[$i] ^ $key[$i % $j]);
        } while ($i--);

        $encryptedText = base64_encode(base64_encode(
            strrev($encryptedText)
        ));
        return $encryptedText;
    }

    /**
     * Desencripta un texto mediante una contraseña
     *
     * @param $message
     * @param $key
     *
     * @return string
     */
    public static function decryptWithKey($message, $key)
    {
        $str = "";
        settype($message, "string");

        $message = base64_decode(base64_decode($message));
        $i       = strlen($message) - 1;
        $j       = strlen($key);
        if (strlen($message) <= 0) {
            return "";
        }
        do {
            $str .= ($message[$i] ^ $key[$i % $j]);
        } while ($i--);
        $str = strrev($str);
        return $str;
    }

    /**
     * Encripta un valor de forma que siempre de diferente.
     * Se le concatena la fecha y hora.
     * Se reemplazan los caracteres +=/ por -,_
     *
     * @param string $str
     *
     * @return string
     */
    public static function encryptRandom($str)
    {
        $numDigits = 6;
        $random     = '';
        for ($n = $numDigits; $n > 0; $n--) {
            $generated = '' . mt_rand();
            $position = mt_rand(1, strlen($generated) - 1);
            $random   .= $generated[$position];
        }

        $url = $str . '#' . $random;

        $encryptedUrl = self::encryptTransposition($url);

        // Sustituimos en el Base64 los caracteres +=/ ya que pueden 
        // dar problemas en la url
        return str_replace(
            array('+', '=', '/'),
            array('-', ',', '_'),
            $encryptedUrl
        );
    }

    /**
     * Desencripta un valor.
     *
     * @param string $str
     *
     * @return string
     */
    public static function decryptRandom($str)
    {
        /*  Restablecemos los caracteres +=/ */
        $encryptedUrl = str_replace(
            array('-', ',', '_'),
            array('+', '=', '/'),
            '' . $str
        );
        $url = self::decryptTransposition($encryptedUrl);

        /* Devolvemos hasta el último separador */
        return substr(
            $url,
            0,
            HelperString::indexOfLast($url, '#')
        );
    }

    /**
     * Genera un número dado de bytes.
     *
     * @param integer $length
     *
     * @return mixed
     */
    public static function randomBytes($length = 16)
    {
        $sslStr = '';

        if (function_exists('openssl_random_pseudo_bytes') && (version_compare(PHP_VERSION, '5.3.4') >= 0 || constant("IS_WIN"))) {
            $sslStr = openssl_random_pseudo_bytes($length, $strong);

            if ($strong) return $sslStr;
        }

        $bitsPerRound = 2;
        $maxTimeMicro = 400;
        $shaHashLength = 20;
        $randomStr = '';
        $total = $length;

        $urandom = false;
        $handle = null;

        if (function_exists('stream_set_read_buffer') && @is_readable('/dev/urandom')) {
            $handle = @fopen('/dev/urandom', 'rb');

            if ($handle) $urandom = true;
        }

        while ($length > strlen($randomStr)) {
            $bytes = ($total > $shaHashLength) ? $shaHashLength : $total;
            $total -= $bytes;

            $entropy = rand() . uniqid(mt_rand(), true) . $sslStr;
            $entropy .= implode('', @fstat(fopen(__FILE__, 'r')));
            $entropy .= memory_get_usage();
            $sslStr = '';

            if ($urandom) {
                stream_set_read_buffer($handle, 0);
                $entropy .= @fread($handle, $bytes);
            } else {
                $samples = 3;
                $duration = 0;

                for ($pass = 0; $pass < $samples; ++$pass) {
                    $microStart = microtime(true) * 1000000;
                    $hash = sha1(mt_rand(), true);

                    for ($count = 0; $count < 50; ++$count) $hash = sha1($hash, true);

                    $microEnd = microtime(true) * 1000000;
                    $entropy .= $microStart . $microEnd;

                    if ($microStart > $microEnd) $microEnd += 1000000;

                    $duration += $microEnd - $microStart;
                }

                $duration = $duration / $samples;

                $rounds = (int) (($maxTimeMicro / $duration) * 50);

                $iter = $bytes * (int) ceil(8 / $bitsPerRound);

                for ($pass = 0; $pass < $iter; ++$pass) {
                    $microStart = microtime(true);
                    $hash = sha1(mt_rand(), true);

                    for ($count = 0; $count < $rounds; ++$count) $hash = sha1($hash, true);

                    $entropy .= $microStart . microtime(true);
                }
            }

            $randomStr .= sha1($entropy, true);
        }

        if ($urandom) @fclose($handle);

        return substr($randomStr, 0, $length);
    }

    /**
     * Crea un hash encriptado con la clave secreta de la configuración.
     *
     * @param string $algorithm
     * @param string $data
     *
     * @return  string
     */
    public static function createHash($algorithm, $data)
    {
        $context = hash_init($algorithm, HASH_HMAC, Settings::$secret);
        hash_update($context, $data);

        return hash_final($context);
    }

    /**
     * Crea una password encriptada.
     *
     * @param string $string
     *
     * @return string
     */
    public static function createPassword($string)
    {
        $salt = HelperString::randomString(64);
        $password = self::createHash('sha1', $string . $salt);

        return $password . ':' . $salt;
    }
}
