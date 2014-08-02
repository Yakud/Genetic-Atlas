<?php
namespace Cache;

/**
 * Реализация кэша в оперативной памяти
 * @author akiselev
 */
class MemoryCache implements CacheInterface {
    /**
     * Кэш в памяти
     * @var array
     */
    protected $cache = array();

    /**
     * Возвраает значение из кэша, если есть значение по указанному ключу
     * Или значение $default
     * @param mixed $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null) {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        return $default;
    }

    /**
     * Кладет значение в кэш
     * @param mixed $key
     * @param mixed $data
     * @return $this
     */
    public function set($key, $data) {
        $this->cache[$key] = $data;
        return $this;
    }

    /**
     * Имеется ли значение по указанному ключу
     * @param mixed $key
     * @return bool
     */
    public function has($key) {
        return array_key_exists($key, $this->cache);
    }

    /**
     * Удаляет значение из кэша по ключу
     * @param mixed $key
     * @return $this
     */
    public function remove($key) {
        if (array_key_exists($key, $this->cache)) {
            unset($this->cache[$key]);
        }

        return $this;
    }

    /**
     * Полностью очищает кэш
     * @return $this
     */
    public function clear() {
        $this->cache = array();
        return $this;
    }
} 