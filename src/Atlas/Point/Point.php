<?php
namespace Atlas\Point;

/**
 * Точка на карте
 * @author akiselev
 */
class Point {
    protected $fields = array();
    protected $Config = null;

    public function __construct(array $data = array()) {
        $this->import($data);
    }

    /**
     * Импортирует массив полей ключ-значение: имя поля-значение
     * @param array $data
     */
    public function import(array $data) {
        $defaults = $this->getConfig()->getDefaults();

        foreach ($data as $filedName => $filedValue) {
            if (isset($defaults[$filedName])) {
                $this->fields[$filedName] = $filedValue;
            }
        }
    }

    /**
     * Возвращает значение ключа
     * @param string $fieldName
     * @return mixed
     */
    public function getFieldValue($fieldName) {
        if (isset($this->fields[$fieldName])) {
            return $this->fields[$fieldName];
        }

        return $this->getConfig()->getDefaultValue($fieldName);
    }

    /**
     * Экпортирует массив полей ключ-значение: имя поля-значение
     * @return array
     */
    public function export() {
        return $this->fields;
    }

    /**
     * @return PointConfig
     */
    protected function getConfig() {
        return PointConfig::getInstance();
    }
} 