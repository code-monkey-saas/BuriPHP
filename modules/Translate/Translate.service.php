<?php

namespace Services;

use BuriPHP\Settings;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Helpers\HelperSession;
use Libraries\BuriPHP\Service;

class Translate extends Service
{
    /**
     * Translate buffer using i18n
     * 
     * @param string $buffer
     * @param string $pathLangFile
     */
    final public function i18n($buffer, $pathLangFile)
    {
        if (isset(getallheaders()['set-language'])) {
            $filename = getallheaders()['set-language'];
        } else if (true === HelperSession::existsValue('set-language')) {
            $filename = HelperSession::getString('set-language');
        } else {
            $filename = Settings::$langDefault;
        }

        $pathLangFile = $pathLangFile . DS . "i18n" . DS . "$filename.json";

        if (true == HelperFile::exists($pathLangFile)) {
            $lang = json_decode(file_get_contents($pathLangFile), true);
            preg_match_all("/\{{2}translate\|[a-zA-Z0-9.-_]+(\|[a-zA-Z0-9.\/]+)?}{2}/", $buffer, $matchAll, PREG_SET_ORDER);

            foreach ($matchAll as $value) {
                $foo = explode('|', $value[0]);
                $foo = str_replace(["{{", "}}"], '', $foo);

                if (count($foo) === 3) {
                    $pathLangFile = $foo[2] . DS . "i18n" . DS . "$filename.json";

                    if (false == HelperFile::exists($pathLangFile)) {
                        break;
                    }

                    $_lang = json_decode(file_get_contents($pathLangFile), true);
                } else {
                    $_lang = $lang;
                }

                $search = explode('.', $foo[1]);

                while (count($search) > 0) {
                    if (isset($search[0])) {
                        if (isset($_lang[$search[0]])) {
                            $_lang = $_lang[$search[0]];
                            array_shift($search);
                        } else {
                            $search = [];
                        }
                    }
                }

                if (is_string($_lang)) {
                    $buffer = str_replace($value[0], $_lang, $buffer);
                }
            }
        }

        return $buffer;
    }
}
