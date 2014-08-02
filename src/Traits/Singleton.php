<?php
namespace Traits;

/**
 * Трейт "одиночки"
 * @author akiselev
 */
trait Singleton {
    /**
     * Экземпляр "одиночки"
     * @var static
     */
    protected static $Instance = null;

    /**
     * Возвращает эксземпляр "одиночки"
     * @return static
     */
    public static function getInstance() {
        if (is_null(static::$Instance)) {
            static::$Instance = new static();
        }

        return static::$Instance;
    }

    // сокрываем от создания вне класса
    protected function __construct() {}
    protected function __clone() {}
    protected function __wakeup() {}
} 