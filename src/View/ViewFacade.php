<?php
namespace View;

/**
 *
 * @author yakud
 */
class ViewFacade {
    /**
     * @var View
     */
    protected static $ViewInstance = null;

    protected function __construct() {}
    protected function __clone() {}

    /**
     * Возвращает экземпляр строителя шаблонов
     * @return View
     */
    public static function getInstance() {
        if (!static::$ViewInstance) {
            static::$ViewInstance = new View();
        }

        return static::$ViewInstance;
    }

    /**
     * Рендерит вьюху
     * @param string $view
     * @param array $data
     * @throws Exception\ViewIncludeException
     * @return string
     */
    public static function make($view, array $data = array()) {
        return static::getInstance()->make($view, $data);
    }

    /**
     * Рендерит вьюху без макета
     * @param string $view
     * @param array $data
     * @throws Exception\ViewIncludeException
     * @return string
     */
    public static function makeWithoutLayout($view, array $data = array()) {
        return static::getInstance()->makeWithoutLayout($view, $data);
    }

    /**
     * Устанавливает заголовок страницы
     * @param string $title
     * @return View
     */
    public static function setTitle($title) {
        return static::getInstance()->setTitle($title);
    }
} 