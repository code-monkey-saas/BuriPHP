<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 1.0
 * @version 2.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Helpers\HelperServer;
use Libraries\BuriPHP\Helpers\HelperString;

class View
{
    /**
     * Renderiza la vista
     * 
     * @final
     *
     * @param object $controller
     * @param mixed $layouts
     *
     * @return string
     */
    final public function render($file, $base = null)
    {
        foreach ($GLOBALS as $key => $value) {
            if (
                $key != 'GLOBALS' ||
                $key != '_SERVER' ||
                $key != '_GET' ||
                $key != '_POST' ||
                $key != '_FILES' ||
                $key != '_COOKIE' ||
                $key != '_SESSION' ||
                $key != '_REQUEST' ||
                $key != '_ENV' ||
                $key != '_APP'
            ) {
                global ${$key};
            }
        }

        /**
         * Obtiene la vista.
         */
        ob_start();
        require HelperFile::getSanitizedPath($file);
        $renderBody = ob_get_contents();
        ob_end_clean();

        /**
         * Obtiene la base principal de las vistas.
         * 
         * Si $base es false, solo debe mostrar $file
         * Si $base es null, debe agregar el default
         * Si $base es no empty, debe usar el que se esta solicitando
         */
        if ($base !== false) {
            ob_start();

            if (is_null($base)) {
                require HelperFile::getSanitizedPath(PATH_SHARED . 'base.php');
            }

            if (!is_null($base)) {
                require HelperFile::getSanitizedPath($base);
            }

            $renderBase = ob_get_contents();

            ob_end_clean();

            $renderBody = str_replace('{{renderView}}', $renderBody, $renderBase);
        }

        $renderBody = $this->importFiles($renderBody);
        $renderBody = $this->replaceVars($renderBody);
        $renderBody = $this->replacePaths($renderBody);
        $renderBody = $this->includesDependencies($renderBody);

        // Eliminamos saltos de linea en blanco.
        $renderBody = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $renderBody);

        return $renderBody;
    }

    /**
     * Establece el título de la página.
     * 
     * @param string $str
     */
    final public function setPageTitle($str)
    {
        $GLOBALS['_APP']['pageTitle'] = serialize($str);
    }

    /**
     * Obtiene el título de la página.
     */
    private function getPageTitle()
    {
        if (!isset($GLOBALS['_APP']['pageTitle'])) {
            return '';
        }

        return unserialize($GLOBALS['_APP']['pageTitle']);
    }

    /**
     * Importa los archivos.
     * 
     * @param string $view
     * 
     * @return string
     */
    private function importFiles($view)
    {
        preg_match_all("/\{{2}import\|[a-zA-Z_\/\.]+\}{2}/", $view, $placeholders, PREG_SET_ORDER);

        foreach ($placeholders as $value) {
            $nameFile = HelperString::getBetween($value[0], '{{import|', '}}');
            $file = explode('/', $nameFile);
            $file = HelperArray::compact($file);

            $file = (count($file) > 1) ?
                HelperFile::getSanitizedPath(PATH_ROOT . DS . HelperArray::joinValues($file, '/')) :
                HelperFile::getSanitizedPath(PATH_SHARED . HelperArray::joinValues($file, '/'));

            if (HelperFile::exists($file)) {
                ob_start();
                require $file;
                $import = ob_get_contents();
                ob_end_clean();
                $view = str_replace('{{import|' . $nameFile . '}}', $import, $view);
                unset($import);
            } else {
                $view = str_replace('{{import|' . $nameFile . '}}', '', $view);
            }
        }

        return $view;
    }

    /**
     * Remplaza las variables html.
     * 
     * @param string $view
     * 
     * @return string
     */
    private function replaceVars($view)
    {
        $varArr = [
            '{$pageTitle}' => $this->getPageTitle(),
            '{$pageBase}' => HelperServer::getDomainHttp()
        ];

        $view = str_replace(array_keys($varArr), array_values($varArr), $view);

        return $view;
    }

    /**
     * Remplaza el path de los assets.
     * {{path|*}} antes {$path.*}.
     * 
     * @param string $view
     * 
     * @return string
     */
    private function replacePaths($view)
    {
        preg_match_all("/\{{2}path\|[a-zA-Z]+\}{2}/", $view, $path, PREG_SET_ORDER);

        foreach ($path as $value) {
            $asset = HelperString::getBetween($value[0], '{{path|', '}}');

            switch ($asset) {
                case 'css':
                    $view = str_replace('{{path|css}}', '/assets/css/', $view);
                    break;

                case 'js':
                    $view = str_replace('{{path|js}}', '/assets/js/', $view);
                    break;

                case 'image':
                    $view = str_replace('{{path|image}}', '/assets/images/', $view);
                    break;

                case 'upload':
                    $view = str_replace('{{path|upload}}', '/assets/uploads/', $view);
                    break;

                case 'plugin':
                    $view = str_replace('{{path|plugin}}', '/assets/plugins/', $view);
                    break;
            }
        }

        return $view;
    }

    /**
     * Incluye las dependencias.
     * {{asset|css|{{path|css}}styles.css|type:text/css|media:all||rel:stylesheet}}
     * 
     * @param string $view
     * 
     * @return string
     */
    private function includesDependencies($view)
    {
        preg_match_all("/\{{2}asset\|[a-zA-Z\|\{\$-=?\.\}\:\/]+}{2}/", $view, $includes, PREG_SET_ORDER);

        $dependencies = [
            'css' => [],
            'js' => []
        ];

        foreach ($includes as $value) {
            $file = HelperString::getBetween($value[0], '{{asset|', '}}');
            $file = explode('|', $file);
            $file = HelperArray::compact($file);

            foreach ($file as $_key => $_value) {
                $x = explode(':', $_value);

                if (count($x) == 2) {
                    $file[$_key] = $x[0] . '="' . $x[1] . '"';
                }
            }

            switch ($file[0]) {
                case 'css':
                    $attributes = "";

                    for ($i = 2; $i < count($file); $i++) {
                        $attributes .= " " . $file[$i];
                    }

                    $dependencies['css'][] = '<link href="' . $file[1] . '"' . $attributes . '/>';
                    break;
                case 'js':
                    $attributes = "";

                    for ($i = 2; $i < count($file); $i++) {
                        $attributes .= " " . $file[$i];
                    }

                    $dependencies['js'][] = '<script src="' . $file[1] . '"' . $attributes . '></script>';
                    break;
            }

            $view = str_replace($value[0], '', $view);
        }

        foreach ($dependencies['css'] as $value) {
            $view = str_replace('{$cssDependencies}', $value . '{$cssDependencies}', $view);
        }

        $view = str_replace('{$cssDependencies}', '', $view);

        foreach ($dependencies['js'] as $value) {
            $view = str_replace('{$jsDependencies}', $value . '{$jsDependencies}', $view);
        }

        $view = str_replace('{$jsDependencies}', '', $view);

        return $view;
    }
}
