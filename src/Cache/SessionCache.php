<?php
namespace Cache;
use Traits\Singleton;

/**
 * Кэш в сессии. Например, для передачи ошибок, служебной информации
 * @author akiselev
 */
class SessionCache implements CacheInterface {
    use Singleton;

    /**
     * Возвраает значение из кэша, если есть значение по указанному ключу
     * Или значение $default
     * @param mixed $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null) {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * Возвращает значение и убирает из кэша
     * @param mixed $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getAndRemove($key, $default = null) {
        $data = $this->get($key, $default);
        $this->remove($key);

        return $data;
    }


    /**
     * Кладет значение в кэш
     * @param mixed $key
     * @param mixed $data
     * @return $this
     */
    public function set($key, $data) {
        $_SESSION[$key] = $data;
        return $this;
    }

    /**
     * Имеется ли значение по указанному ключу
     * @param mixed $key
     * @return bool
     */
    public function has($key) {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Удаляет значение из кэша по ключу
     * @param mixed $key
     * @return $this
     */
    public function remove($key) {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }

        return $this;
    }

    /**
     * Полностью очищает кэш
     * @return $this
     */
    public function clear() {
        $_SESSION = array();
        return $this;
    }
}