<?php
namespace Atlas\Point;

use Storage\RedisStorage;

/**
 * Быстрое редисковое хранилище точек
 * @author yakud
 */
class PointRedisStorage extends RedisStorage {
    const STORAGE_NAME   = 'points';
    const KEY_POINT      = 'point';
    const SEPARATOR      = ':';
    const KEY_COUNTER_ID = 'counter_id';

    /**
     * Список индексов
     */
    const INDEX_ID            = 'index_id';            // Индекс по id
    const INDEX_POPULATION_ID = 'index_population_id'; // Индекс по population_id

    /**
     * @var Point[]
     */
    protected $applyingPoints = array();

    /**
     * @param Point $Point
     */
    public function applyPoint(Point $Point) {
        $this->applyingPoints[] = $Point;
    }

    /**
     * Сохраняет точку в сторадж
     * @param Point $Point
     * @return int
     */
    public function save(Point $Point) {
        $pointId = $Point->getFieldValue('id');
        if (!$pointId) {
            // Не передали ID, сгенерируем новый
            $pointId = $this->incrementCounterPointId();
            $Point->import(['id' => $pointId]);
        }

        $data = json_encode($Point->export());

        // Добавим точку
        $storageKey = $this->makeKeyPoint($pointId);
        $this->executeRaw(['SET', $storageKey, $data]);

        // Обновим индекс
        $this->updatePointIndex($Point);

        return $pointId;
    }

    /**
     * Удаляет точку из стораджа
     * @param Point $Point
     * @return mixed
     */
    public function delete(Point $Point) {
        $pointId = $Point->getFieldValue('id');

        $storageKey = $this->makeKeyPoint($pointId);
        $this->executeRaw(['DEL', $storageKey]);

        $this->removePointFromIndex($Point);
        $Point = null;

        return true;
    }

    /**
     * Возвращает точку по ID
     * @param int $id
     * @return Point|null
     */
    public function getPointById($id) {
        $storageKey = $this->makeKeyPoint($id);

        $data = $this->executeRaw(['GET', $storageKey]);
        if ($data) {
            $data = json_decode($data, true);
            return new Point($data);
        }

        return null;
    }

    /**
     * Обновляет индексы для точки
     * @param Point $Point
     * @return bool
     */
    public function updatePointIndex(Point $Point) {
        $id           = $Point->getFieldValue('id');
        $populationId = $Point->getFieldValue('population_id');
        $oldData      = $Point->exportOld();

        if (!$id) {
            return false;
        }

        $this->executeRaw(['SADD', $this->makeKey(self::INDEX_ID), $id]);

        if (array_key_exists('population_id', $oldData)) {
            $this->removeFromIndex(self::INDEX_POPULATION_ID, $oldData['population_id'], $id);
        }
        $this->addToIndex(self::INDEX_POPULATION_ID, $populationId, $id);

        return true;
    }

    /**
     * Удаляет точку из индексов
     * @param Point $Point
     * @return bool
     */
    public function removePointFromIndex(Point $Point) {
        $id           = $Point->getFieldValue('id');
        $populationId = $Point->getFieldValue('population_id');

        if (!$id) {
            return false;
        }

        $this->executeRaw(['SREM', $this->makeKey(self::INDEX_ID), $id]);
        $this->removeFromIndex(self::INDEX_ID, $populationId, $id);

        return true;
    }

    /**
     * Добавляет элемент в индекс
     * @param string $index
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    public function addToIndex($index, $key, $value) {
        $indexKey = $this->makeKeyIndex($index, $key);
        return $this->executeRaw(['SADD', $indexKey, $value]);
    }

    /**
     * Удаляет данные из индекса
     * @param string $index
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    public function removeFromIndex($index, $key, $value) {
        $indexKey = $this->makeKeyIndex($index, $key);
        return $this->executeRaw(['SREM', $indexKey, $value]);
    }

    /**
     * Очищает индекс
     * @param string $index
     * @param $key
     * @return mixed
     */
    public function clearIndex($index, $key) {
        $indexKey = $this->makeKeyIndex($index, $key);
        return $this->executeRaw(['HDEL', $indexKey]);
    }

    /**
     * Возвращает список всех идентификаторов точек
     * @return array
     */
    public function getPointsIds() {
        return $this->executeRaw(['SMEMBERS', $this->makeKey(self::INDEX_ID)]);
    }

    /**
     * Увеличивает счетик ID'ков
     * @return mixed
     */
    protected function incrementCounterPointId() {
        $key = $this->makeKey(static::KEY_COUNTER_ID);
        return $this->executeRaw(['INCR', $key]);
    }

    /**
     * Сбрасывает счетчик ID'ков
     * @return mixed
     */
    public function resetCounterPointId() {
        $key = $this->makeKey(static::KEY_COUNTER_ID);
        return $this->executeRaw(['DEL', $key]);
    }

    /**
     * Ключ точки по ID
     * @param int $id
     * @return string
     */
    protected function makeKeyPoint($id) {
        return $this->makeKey(static::KEY_POINT . static::SEPARATOR . $id);
    }

    /**
     * Ключ для индекса
     * @param string $index
     * @param string $key
     * @return string
     */
    protected function makeKeyIndex($index, $key) {
        return $this->makeKey($index . self::SEPARATOR . $key);
    }

    /**
     * Создает ключ с префиксом сторраджа
     * @param string $key
     * @return string
     */
    protected function makeKey($key = '') {
        return static::STORAGE_NAME . static::SEPARATOR . $key;
    }
}