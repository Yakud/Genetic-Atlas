<?php
namespace Atlas\Point;

/**
 *
 * Core types ElasticSearch:
 *   string, integer/long, float/double, boolean, null
 *
 * @author akiselev
 */
class PointConfig {
    /**
     * Стораджи
     */
    const STORAGE_ELASTIC = 'elastic';
    const STORAGE_REDIS   = 'redis';

    /**
     * @var PointConfig|null
     */
    protected static $Instance = null;

    /**
     * @return PointConfig
     */
    public static function getInstance() {
        if (!static::$Instance) {
            static::$Instance = new static();
        }

        return static::$Instance;
    }

    protected function __construct(){}

    protected $config = array(
        'id' => array(
            'default' => 0,
        ),
        'type' => array(
            'default' => '',
        ),
        'lat' => array(
            'default' => 0.0,
        ),
        'lon' => array(
            'default' => 0.0,
        ),
    );

    /**
     * Возвращает массив ключ-значение: имя поля-значение по умолчанию
     * @return array
     */
    public function getDefaults() {
        $defaults = array();

        foreach ($this->config as $fieldName => $fieldData) {
            if (isset($fieldData['default'])) {
                $defaults[$fieldName] = $fieldData['default'];
            } else {
                $defaults[$fieldName] = null;
            }
        }

        return $defaults;
    }

    /**
     * Возвращает значение по умолчанию для ключа
     * @param string $fieldName
     * @return mixed
     */
    public function getDefaultValue($fieldName) {
        if (isset($this->config[$fieldName]['default'])) {
            return $this->config[$fieldName]['default'];
        }

        return null;
    }
} 