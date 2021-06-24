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

trait Controller
{
    /**
    *
    * @var object
    */
    public $view;

    /**
    *
    * @var object
    */
    public $security;

    /**
    *
    * @var object
    */
    public $format;

    /**
    *
    * @var object
    */
    public $session;

    /**
    *
    * @var object
    */
    public $system;

    /**
    *
    * @var object
    */
    public $model;

    /**
    * Guarda el lenguaje establecido.
    *
    * @var string
    */
    public $_lang;

    /**
    * Constructor.
    *
    * @return  void
    */

    public function __construct()
    {
        $this->view = new View();
        $this->security = new Security();
        $this->format = new Format();
        $this->sesion = new Session();
        $this->_lang = Session::get_value('_lang');

        $class_model = str_replace('Controllers', 'Models', get_class($this));
        $this->model = new $class_model();
    }
}
