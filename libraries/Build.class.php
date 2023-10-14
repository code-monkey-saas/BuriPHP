<?php

namespace Libraries\Build;

use BuriPHP\Settings;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Libraries\BuriPHP\Database;
use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperHeader;
use Libraries\BuriPHP\Helpers\HelperServer;
use Libraries\BuriPHP\Helpers\HelperSession;
use Libraries\BuriPHP\Helpers\HelperValidate;
use Libraries\Responses;

class Build
{
    private $endpoint;

    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function startup()
    {
        if (!HelperSession::isActive()) {
            HelperSession::init();
        }

        if (!HelperValidate::isEmpty(HelperServer::getValue('HTTP_AUTHORIZATION'))) {
            $jwt = str_replace('Bearer ', '', HelperServer::getValue('HTTP_AUTHORIZATION'));
        } else if (HelperSession::existsValue('authorization')) {
            $jwt = HelperSession::getString('authorization');
        } else {
            $jwt = null;
        }

        try {
            if (isset($this->endpoint['SETTINGS']['Auth']) && $this->endpoint['SETTINGS']['Auth'] === 'required') {
                if (is_null($jwt)) {
                    throw new \Exception('No está autorizado para acceder.');
                }

                $token = json_decode(json_encode(JWT::decode($jwt, new Key(Settings::$secret, 'HS256'))), true);
                $pushSession = [];

                $database = (new Database())->newInstance();

                /**
                 * Find user permissions
                 */
                $user = $database->select('USER', [
                    '[>]PERMISSION' => [
                        'PERMISSION_ID' => 'ID'
                    ]
                ], [
                    "PERMISSION.PERMISSION",
                    "PERMISSION.VALUE"
                ], [
                    'USER.ID' => $token['user']['userId']
                ]);

                $user = Database::snakeToCamel($user)[0];

                $permission = !is_null($user["permission"]) ? [
                    $user["permission"],
                    !is_null($user["value"]) ? explode(',', $user["value"]) : []
                ] : null;

                $pushSession = HelperArray::combine($pushSession, ["permission" => $permission]);
                /** END */

                /**
                 * Find user subscription
                 */
                $subscription = $database->select('SUBSCRIPTION', [
                    "STATUS",
                    "DATA [Object]",
                ], [
                    'USER_ID' => $token['user']['userId']
                ]);

                $subscription = isset($subscription[0]) ? $subscription[0] : null;

                $pushSession = HelperArray::combine($pushSession, ["subscription" => $subscription]);
                /** END */

                $GLOBALS['_APP']['SESSION'] = HelperArray::combine($token, $pushSession);
            }

            if (!is_null($jwt) && isset($this->endpoint['SETTINGS']['OnlyPublic']) && $this->endpoint['SETTINGS']['OnlyPublic']) {
                throw new \Exception("onlyPublic");
            }
        } catch (\Exception $e) {
            if ($this->endpoint['CONTENT_TYPE'] == 'json') {
                echo json_encode(Responses::response(401, [
                    'message' => $e->getMessage()
                ]), JSON_PRETTY_PRINT);

                return false;
            } else {
                $ref = "/" . implode('/', HelperServer::getCurrentPathInfo());

                if ($e->getMessage() === "Expired token") {
                    list($header, $payload, $signature) = explode(".", $jwt);
                    $payload = json_decode(base64_decode($payload), true);

                    HelperSession::destroy();

                    HelperHeader::goLocation('/');
                } else {
                    HelperHeader::goLocation('/');

                    return false;
                }
            }
        }
    }

    public function wakeup()
    {
    }
}
