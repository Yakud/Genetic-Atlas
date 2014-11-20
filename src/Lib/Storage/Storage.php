<?php
namespace Lib\Storage;

/**
 *
 * @author akiselev
 */
class Storage {
    /**
     * Храним данные тут
     * @var array
     */
    protected $values = [];

    /**
     * @param array $source
     */
    public function __construct(array $source = []) {
        $this->values = $this->getDefault();

        if ($source) {
            $this->merge($source);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return array_key_exists($key, $this->values);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return array_key_exists($key, $this->values) ? $this->values[$key] : $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value) {
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * @param array $source
     */
    public function merge(array $source) {
        $this->values = array_merge($this->values, $source);
    }

    /**
     * @param array $source
     * @return $this
     */
    public function import(array $source) {
        $this->values = $this->getDefault();
        $this->merge($source);
        return $this;
    }

    /**
     * @return array
     */
    public function export() {
        return $this->values;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function del($key) {
        if (array_key_exists($key, $this->values)) {
            unset($key);
        }

        return $this;
    }

    public function getConfig() {
        return [];
    }

    public function getDefault() {
        $config = $this->getConfig();
        $defaults = [];
        foreach ($config as $key => $value) {
            $default = null;
            if (is_array($value) && array_key_exists('default', $value)) {
                $default = $value['default'];
            } else {
                $default = $value;
            }

            $defaults[$key] = $default;
        }

        return $defaults;
    }
} 