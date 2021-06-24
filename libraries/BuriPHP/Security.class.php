<?php namespace BuriPHP\System\Libraries;

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

class Security
{
    /**
    * Obtiene la url dividida en array.
    *
    * @static
    *
    * @return  string
    */
    public static function url()
    {
        if ( isset($_SERVER['PATH_INFO']) ) $PATH_INFO = $_SERVER['PATH_INFO'];
        else if ( isset($_SERVER['ORIG_PATH_INFO']) ) $PATH_INFO = $_SERVER['ORIG_PATH_INFO'];
        else $PATH_INFO = null;

        if ( isset($PATH_INFO) && !is_null($PATH_INFO) )
        {
            $url = explode('/', $PATH_INFO);

            $params = [];

            foreach ( $url as $key => $value )
            {
                if ( empty($value) ) unset($url[$key]);
                else $params[] = self::clean_string($value);
            }

            unset($url);

            $params[0] = !empty($params) ? "{$params[0]}" : "";

            return $params;
        }
        else return ["/"];
    }

    /**
    * Quita caracteres especiales de un string.
    *
    * @static
    *
    * @param   string    $str    Cadena de texto
    *
    * @return  string
    */
    public static function clean_string( $str )
    {
        if ( $str !== false )
        {
            $str = trim( $str );
            $str = preg_replace('/[ ]{2}/', ' ', $str);
            $str = str_replace(['á', 'à', 'ä', 'â', 'ª'], 'a', $str);
            $str = str_replace(['Á', 'À', 'Â', 'Ä'], 'A', $str);
            $str = str_replace(['é', 'è', 'ë', 'ê'], 'e', $str);
            $str = str_replace(['É', 'È', 'Ê', 'Ë'], 'E', $str);
            $str = str_replace(['í', 'ì', 'ï', 'î'], 'i', $str);
            $str = str_replace(['Í', 'Ì', 'Ï', 'Î'], 'I', $str);
            $str = str_replace(['ó', 'ò', 'ö', 'ô'], 'o', $str);
            $str = str_replace(['Ó', 'Ò', 'Ö', 'Ô'], 'O', $str);
            $str = str_replace(['ú', 'ù', 'ü', 'û'], 'u', $str);
            $str = str_replace(['Ú', 'Ù', 'Û', 'Ü'], 'U', $str);
            $str = str_replace('ñ', 'n', $str);
            $str = str_replace('Ñ', 'N', $str);
            $str = str_replace(' ', '_', $str);
            $str = preg_replace('/[^ A-Za-z0-9_\-]/', '', $str);
            $str = str_replace(["\\", "\r\n", "\n", "¨", "ç", "Ç", "º", "~", "#", "@", "|", "!", '"', "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "<code>", "]", "+", "}", "{", "¨", "´", ">", "< ", ";", ",", ":", "."], '', $str);
        }

        return $str;
    }

    /**
    * Remplaza los slashes de un uri por los default del sistema.
    *
    * @static
    *
    * @param   string    $path    URI
    * @return  string
    */
    public static function DS( $path )
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $first_character = substr($path, 0, 1);

        $parts = explode(DIRECTORY_SEPARATOR, $path);

        $return = "";

        foreach ( $parts as $value )
        {
            if ( !empty($value) ) $return .= $value . DIRECTORY_SEPARATOR;
        }

        $return = substr($return, 0, -1);

        if ( $first_character == DIRECTORY_SEPARATOR ) return DIRECTORY_SEPARATOR . $return;
        else return $return;
    }

    /**
    * Obtiene el protocolo Web en uso.
    *
    * @static
    *
    * @return  string
    */
    public static function protocol()
    {
        return ( isset($_SERVER['HTTPS']) ) ? "https://" : "http://";
    }

    /**
    * Crea un hash encriptado con la clave secreta de la configuración.
    *
    * @param   string    $algorithm    Tipo de algoritmo para usar.
    * @param   string    $data
    *
    * @return  string
    */
    public function create_hash( $algorithm, $data )
    {
        $context = hash_init($algorithm, HASH_HMAC, \BuriPHP\Configuration::$secret);
        hash_update($context, $data);

        return hash_final($context);
    }

    /**
    * Crea una password encriptada.
    *
    * @param   string    $string    Password para encriptar.
    *
    * @return  string
    */
    public function create_password( $string )
    {
        $salt = $this->random_string(64);
        $password = $this->create_hash('sha1', $string . $salt);

        return $password . ':' . $salt;
    }

    /**
    * Genera un numero dado de bytes.
    *
    * @param   integer    $length    Numero de bytes.
    *
    * @return  mixed
    */
    public function random_bytes( $length = 16 )
    {
        $sslStr = '';

        if (function_exists('openssl_random_pseudo_bytes') && (version_compare(PHP_VERSION, '5.3.4') >= 0 || IS_WIN))
        {
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

        if (function_exists('stream_set_read_buffer') && @is_readable('/dev/urandom'))
        {
            $handle = @fopen('/dev/urandom', 'rb');

            if ($handle) $urandom = true;
        }

        while ($length > strlen($randomStr))
        {
            $bytes = ($total > $shaHashLength) ? $shaHashLength : $total;
            $total -= $bytes;

            $entropy = rand() . uniqid(mt_rand(), true) . $sslStr;
            $entropy .= implode('', @fstat(fopen(__FILE__, 'r')));
            $entropy .= memory_get_usage();
            $sslStr = '';

            if ($urandom)
            {
                stream_set_read_buffer($handle, 0);
                $entropy .= @fread($handle, $bytes);
            }
            else
            {
                $samples = 3;
                $duration = 0;

                for ($pass = 0; $pass < $samples; ++$pass)
                {
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

                for ($pass = 0; $pass < $iter; ++$pass)
                {
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
    * Genera un string de caracteres random.
    *
    * @param   integer    $length    Tamaño del string.
    *
    * @return  string
    */
    public function random_string( $length = 8 )
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $base = strlen($salt);
        $stringRandom = '';

        $random = $this->random_bytes($length + 1);
        $shift = ord($random[0]);

        for ( $i = 1; $i <= $length; ++$i )
        {
            $stringRandom .= $salt[($shift + ord($random[$i])) % $base];
            $shift += ord($random[$i]);
        }

        return $stringRandom;
    }

    /**
    * Obtiene el navegador del cliente.
    *
    * @return  string
    */
    static public function get_browser()
    {
        $browser = "OTHER";

        foreach ( ["IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME"] as $parent )
        {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/','',$version);

            if ( $s ) $browser = $parent;
        }

        return $browser;
    }

    /**
    * Obtiene la IP del cliente.
    *
    * @return  string
    */
    static public function get_ip()
    {
        if (getenv('HTTP_CLIENT_IP')) return getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR')) return getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED')) return getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR')) return getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED')) return getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR')) return getenv('REMOTE_ADDR');
        else return 'UNKNOWN';
    }

    /**
    * Obtiene el sistema operativo del cliente.
    *
    * @return  string
    */
    static function get_os()
    {
        $os = 'OTHER';

        foreach ( ["WIN","MAC","LINUX"] as $val )
        {
            if ( strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val) !== false ) $os = $val;
        }

        return $os;
    }

    /**
    * Obtiene dispositivo movil del cliente.
    *
    * @return  string
    */
    static public function get_mobile_device()
    {
        if ( stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) return "iPad";
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) return "iPhone";
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) return "BlackBerry";
        else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) return "Android";
        else return "Otro";
    }

    /**
    * Obtiene el tipo de dispositivo del cliente.
    *
    * @return  string
    */
    static public function get_type_device()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if ( preg_match( '/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) $tablet_browser++;
        if ( preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) $mobile_browser++;
        if ( ( strpos( strtolower( $_SERVER['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) > 0 ) or ( ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) or isset( $_SERVER['HTTP_PROFILE'] ) ) ) ) $mobile_browser++;

        $mobile_ua = strtolower( substr( $_SERVER['HTTP_USER_AGENT'], 0, 4 ) );
        $mobile_agents = [
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-'
        ];

        if ( in_array( $mobile_ua, $mobile_agents ) ) $mobile_browser++;

        if ( strpos( strtolower( $_SERVER['HTTP_USER_AGENT'] ), 'opera mini' ) > 0 )
        {
            $mobile_browser++;

            $stock_ua = strtolower( isset( $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] ) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : ( isset( $_SERVER['HTTP_DEVICE_STOCK_UA'] ) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : '' ) );

            if ( preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua) ) $tablet_browser++;
        }

        if ( $tablet_browser > 0 ) return 'tablet';
        else if ( $mobile_browser > 0 ) return 'mobile';
        else return 'desktop';
    }

    /**
    * Obtiene el toda la información de conexión del cliente.
    *
    * @return  array
    */
    static public function get_client_info()
    {
        $info['ip'] = Self::get_ip();
        $info['browser'] = Self::get_browser();
        $info['device'] = ucfirst(Self::get_type_device());

        if ( $info['device'] == ucfirst('tablet') || $info['device'] == ucfirst('mobile') ) $info['so'] = Self::get_mobile_device();
        else $info['so'] = Self::get_os();

        $info['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

        return $info;
    }
}
