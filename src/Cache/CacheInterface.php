<?php
namespace Cache;

/**
 * Интерфейс для класса о работе с кэшем
 * @author akiselev
 */
interface CacheInterface {
    /**
     * Возвраает значение из кэша, если есть значение по указанному ключу
     * Или значение $default
     * @param mixed $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Кладет значение в кэш
     * @param mixed $key
     * @param mixed $data
     * @return $this
     */
    public function set($key, $data);

    /**
     * Имеется ли значение по указанному ключу
     * @param mixed $key
     * @return bool
     */
    public function has($key);

    /**
     * Удаляет значение из кэша по ключу
     * @param mixed $key
     * @return $this
     */
    public function remove($key);

    /**
     * Полностью очищает кэш
     * @return $this
     */
    public function clear();
} 