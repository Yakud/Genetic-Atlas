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
            'description' => 'ID',
        ),
        'type' => array(
            'default' => null,
            'description' => 'Тип',
        ),
        'lat' => array(
            'default' => 0.0,
            'description' => 'Широта',
        ),
        'lon' => array(
            'default' => 0.0,
            'description' => 'Долгота',
        ),
        'population_id' => array(
            'default' => '',
            'description' => 'ID популяции',
        ),
        'verbose_population_id' => array(
            'default' => '',
            'description' => 'ID популяции (полный)',
        ),
        'region' => array(
            'default' => '',
            'description' => 'Регион',
        ),
        'country' => array(
            'default' => '',
            'description' => 'Страна',
        ),
        'town' => array(
            'default' => '',
            'description' => 'Город',
        ),
        'sample_id' => array(
            'default' => '',
            'description' => 'ID образца',
        ),
        'sex' => array(
            'default' => 0,
            'description' => 'Пол',
        ),
        'in_analysis_dataset' => array(
            'default' => 0,
            'description' => 'В анализе набора данных',
        ),
        'release_conditions' => array(
            'default' => 0,
            'description' => 'Кандидат',
        ),
        'contributor' => array(
            'default' => '',
            'description' => 'Источник',
        ),
    );

    /**
     * Возвращает массив ключ-значение: имя поля-значение по умолчанию
     * @return array
     */
    public function getDefaults() {
        $defaults = array();

        foreach ($this->config as $fieldName => $fieldData) {
            if (array_key_exists('default', $fieldData)) {
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
        if (array_key_exists('default', $this->config[$fieldName])) {
            return $this->config[$fieldName]['default'];
        }

        return null;
    }
} 