<?php

namespace Libraries;

use Libraries\BuriPHP\Helpers\HelperServer;
use Libraries\BuriPHP\Helpers\HelperSession;
use Libraries\BuriPHP\Helpers\HelperValidate;

class Functions
{
    public static function getSession()
    {
        if (!HelperValidate::isEmpty(HelperServer::getValue('HTTP_AUTHORIZATION'))) {
            $jwt = str_replace('Bearer ', '', HelperServer::getValue('HTTP_AUTHORIZATION'));
        } else if (!HelperValidate::isEmpty(HelperSession::existsValue('authorization'))) {
            $jwt = HelperSession::getString('authorization');
        }

        if (!is_null($jwt) && isset($GLOBALS['_APP']['SESSION'])) {
            return [
                "encode" => $jwt,
                "decode" => $GLOBALS['_APP']['SESSION']
            ];
        } else {
            return false;
        }
    }
}
