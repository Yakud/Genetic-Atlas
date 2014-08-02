<?php
namespace Routing;

/**
 * Класс позволяющий редиректить запрос
 * @author akiselev
 */
class Redirector {
    /**
     * Редиректит по указанному пути
     * @param string $path
     * @param int $code
     */
    public static function to($path, $code = 302) {
        header('Location: ' . $path, true, $code);
        die();
    }
} 