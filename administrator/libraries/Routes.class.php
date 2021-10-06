<?php namespace BuriPHP\Administrator\Libraries;

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

use \BuriPHP\System\Libraries\{Format,Errors,Security};

class Routes
{
    private $path;
    private $settings_url;

    public function __construct($path, $settings_url, $component = null)
    {
        $this->path = $path;
        $this->settings_url = $settings_url;
        $this->component = $component;
    }

    public function on_change_start()
    {
        if ( isset($this->settings_url['petition']) )
        {
            switch ($this->settings_url['petition']) {
                case 'http':
                    Format::no_ajax();
                    break;

                case 'ajax':
                    if ( Format::exist_ajax_request() == false ) die();
                    break;
            }
        }

        if ( isset($_GET['validate']) && $_GET['validate'] === 'image' )
        {
            if ( Format::exist_ajax_request() == true )
            {
                $image = Upload::validate_file($_FILES['image'], ['jpg', 'jpeg', 'png']);

                if ( $image['status'] == 'OK' )
                {
                    $token = (new Security())->random_string('5');

                    echo json_encode([
                        'status' => 'OK',
                        'token' => $token
                    ], JSON_PRETTY_PRINT);
                }
                else
                {
                    echo json_encode([
                        'status' => 'fatal_error',
                        'message' => $image['message']
                    ], JSON_PRETTY_PRINT);
                }

                die();
            }
        }
    }

    public function on_change_end()
    {
        // TODO
    }
}