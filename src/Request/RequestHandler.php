<?php
namespace Request;

/**
 * Обработчик запросов
 * @author akiselev
 */
class RequestHandler {
    /**
     * Возвращает запрос, не включая GET параметры
     * Например:
     *   для    site.ru/hello/world/?a=b
     *   вернет hello/world
     *
     *   для    site.ru?a=b
     *   вернет /
     *
     * @return string
     */
    public static function getRequest() {
        $request     = isset($_SERVER['REQUEST_URI'])  ? $_SERVER['REQUEST_URI']  : '/';
        $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

        if ($queryString) {
            $request = str_replace("?{$queryString}", '', $request);
        }

        $request = explode('/', $request); // разделяем запрос
        $request = array_filter($request); // очищаем
        $request = implode('/', $request); // собираем обратно

        if (!$request) {
            $request = '/';
        }

        return $request;
    }

    /**
     * Возвращает значение из $_GET или $default
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        }

        return $default;
    }

    /**
     * Возвращает значение из $_POST или $default
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function post($key, $default = null) {
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }

        return $default;
    }

    /**
     * Ловим ajax запрос?
     * @return bool
     */
    public static function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
} 