<?php
namespace Response;

/**
 * Обработчик ответа
 * @author akiselev
 */
class ResponseHandler {
    /**
     * @param array $data
     * @return string
     */
    public static function json(array $data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }
} 