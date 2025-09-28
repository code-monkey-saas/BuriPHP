<?php

namespace Core;

use Core\Helpers\HelperHeader;

/**
 * Clase Response
 * 
 * Proporciona funcionalidad para enviar respuestas HTTP estandarizadas en formato JSON.
 * Esta clase se utiliza para formatear y enviar respuestas consistentes desde la API.
 * 
 * @package Core
 * @author Kiske
 * @since 2.1
 * @version 0.2
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * @final
 */
final class Response
{
    /**
     * Envía una respuesta HTTP en formato JSON con un código de estado, datos y mensaje opcionales.
     * 
     * @param array $args Argumentos para la respuesta
     * @param int $args['statusCode'] Código de estado HTTP (por defecto: 200)
     * @param mixed $args['data'] Datos a incluir en la respuesta (opcional)
     * @param string $args['message'] Mensaje descriptivo (opcional)
     * @return void Termina la ejecución del script después de enviar la respuesta
     */
    static function sendResponse(...$args)
    {
        $statusCode = $args['statusCode'] ?? 200;
        $data = $args['data'] ?? null;
        $message = $args['message'] ?? null;

        http_response_code($statusCode);
        header('Content-Type: application/json');

        $status = HelperHeader::getStatusCode($statusCode);

        $response = [
            'code' => $status['code'],
            'status' => $status['status']
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!is_null($message)) {
            $response['message'] = $message;
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}
