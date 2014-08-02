<?php
namespace Routing;
use Controller\Controller;
use Request\RequestHandler;
use Routing\Exception\RouteNotFoundException;

/**
 * Роутер
 * @author akiselev
 */
class Router {
    /**
     * Правила для роутинга
     * @return array
     */
    public function getRules() {
        return array(
            '/'          => 'pages\\MainController@run',
            'points/get' => 'pages\\PointController@getPoints',
        );
    }

    /**
     * Возвращает контроллер
     * @return Controller
     * @throws Exception\RouteNotFoundException
     */
    public function getPageController() {
        $rules   = $this->getRules();
        $request = RequestHandler::getRequest();

        foreach ($rules as $rule => $page) {
            if ($this->isSuitableRequest($request, $rule)) {
                $classController = $this->parseController($page);
                $event           = $this->parseEvent($page);

                return new $classController($event);
            }
        }

        throw new RouteNotFoundException("Route for request \"{$request}\" not found");
    }

    /**
     * Проверяет, подходящее ли правило под запрос
     * @param string $request
     * @param string $rule
     * @return bool
     */
    protected function isSuitableRequest($request, $rule) {
        if ($request == $rule) {
            return true;
        }

        return false;
    }

    /**
     * @param string $str
     * @return string
     */
    protected function parseController($str) {
        $str = explode("@", $str);

        return $str[0];
    }

    /**
     * @param string $str
     * @param mixed $default
     * @return string|mixed
     */
    protected function parseEvent($str, $default = null) {
        $str = explode("@", $str);

        if (array_key_exists(1, $str)) {
            return $str[1];
        }

        return $default;
    }
} 