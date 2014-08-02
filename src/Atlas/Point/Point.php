<?php
namespace Atlas\Point;

/**
 * Точка на карте
 * @author akiselev
 */
class Point {
    /**
     * Текущие данные точки
     * @var array
     */
    protected $fields = array();

    /**
     * Данные которые в базе или были загружены первый раз
     * @var array
     */
    protected $fieldsOld  = array();

    /**
     * Импортированы ли данные в модель
     * @var bool
     */
    protected $isImported = false;

    /**
     * @var PointConfig
     */
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
            if (array_key_exists($filedName, $defaults)) {
                $this->fields[$filedName] = $filedValue;

                if (!$this->isImported) {
                    $this->fields[$filedName] = $filedValue;
                }
            }
        }

        $this->isImported = true;
    }

    /**
     * Возвращает значение ключа
     * @param string $fieldName
     * @return mixed
     */
    public function getFieldValue($fieldName) {
        if (array_key_exists($fieldName, $this->fields)) {
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

    public function exportOld() {
        return $this->fieldsOld;
    }

    /**
     * @return PointConfig
     */
    protected function getConfig() {
        return PointConfig::getInstance();
    }
} 