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

class Component
{
    private function url_private_base( $component )
    {
        return ( Format::check_path_admin() ) ? Security::DS(PATH_ADMINISTRATOR_COMPONENTS . $component) : Security::DS(PATH_COMPONENTS . $component);
    }

    private function get_namespace( $component )
    {
        return ( Format::check_path_admin() ) ? "\BuriPHP\Administrator\Components\\". $component ."\\" : "\BuriPHP\Components\\". $component ."\\";
    }

    public static function urls( $component = null )
    {
        if ( is_null($component) )
            return [];

        $uri = (new self)->url_private_base($component) .'/Component'. CLASS_PHP;

        if ( file_exists($uri) )
        {
            while ( !class_exists((new self)->get_namespace($component) .'Component') ) :
                require $uri;
            endwhile;

            $namespace = (new self)->get_namespace($component) . 'Component';

            if ( method_exists($namespace, 'urls') )
                return $namespace::urls();
            else Errors::system(null, 'The method to call urls in component <i>'. $component .'</i> in URLS_REGISTERED does not exist.');
        }
        else Errors::system(null, 'The file <i>'. $uri .'</i> calling in URLS_REGISTERED does not exist.');
    }

    public function import( $argv = [] )
	{
		if ( !isset($argv['component']) || !isset($argv['controller']) || !isset($argv['method']) )
			return false;

		if ( !isset($argv['params']) )
			$argv['params'] = [];

		$layout = ( Format::check_path_admin() ) ? '\BuriPHP\Administrator\System\Libraries\Layout' : '\BuriPHP\System\Libraries\Layout';

		$component = new $layout();
		$component->set_settings_page($argv);

		ob_start();
			$component->load_core();
			$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

    public function execute( $argv = [] )
    {
        if ( !isset($argv['component']) || !isset($argv['controller']) || !isset($argv['method']) )
			return false;

		if ( !isset($argv['params']) )
			$argv['params'] = [];

        $uri = $this->url_private_base($argv['component']) .'/Component'. CLASS_PHP;
        
        if ( file_exists($uri) )
        {
            if ( !array_search($uri, get_included_files()) ) :
                include $uri;
            endif;

            $controller_file = $this->url_private_base($argv['component']) .'/'. $argv['controller']. CONTROLLER_PHP;
            $model_file = $this->url_private_base($argv['component']) .'/'. $argv['controller']. MODEL_PHP;

            if ( !array_search($controller_file, get_included_files()) ) :
                include $controller_file;
            endif;

            if ( !array_search($model_file, get_included_files()) ) :
                include $model_file;
            endif;

            $namespace = (new self)->get_namespace($argv['component']) . 'Controllers\\'. $argv['controller'];

            $controller = new $namespace();

            return $controller->{$argv['method']}( $argv['params'] );
        }
        else Errors::system(null, 'The file <i>'. $uri .'</i> does not exist.');
    }
}
