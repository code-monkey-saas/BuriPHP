<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'defines.php';
require_once PATH_CORE . 'Autoloader.class.php';

Core\Autoloader::register();
Core\Application::getInstance()->initialize();
