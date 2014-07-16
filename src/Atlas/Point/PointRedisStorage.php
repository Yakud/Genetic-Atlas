<?php
namespace Atlas\Point;

use Storage\RedisStorage;

/**
 * Быстрое редисковое хранилище точек
 * @author yakud
 */
class PointRedisStorage extends RedisStorage {

    const STORAGE_NAME = 'points';
    const KEY_POINT_ID = '_id';
    const KEY_POINT    = 'point';
    const SEPARATOR    = ':';

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
            $pointId = $this->incrementPointId();
            $Point->import(['id' => $pointId]);
        }

        $data = $Point->export();
        $data = json_encode($data);

        $storageKey = $this->makeKeyPoint($pointId);
        $this->getClient()->executeRaw(['SET', $storageKey, $data]);

        return $pointId;
    }

    /**
     * @param int $id
     * @return Point|null
     */
    public function getPointById($id) {
        $storageKey = $this->makeKeyPoint($id);

        $data = $this->getClient()->executeRaw(['GET', $storageKey]);
        if ($data) {
            $data = json_decode($data, true);
            return new Point($data);
        }

        return null;
    }

    protected function incrementPointId() {
        $key = $this->makeKeyPointId();
        return $this->getClient()->executeRaw(['INCR', $key]);
    }

    public function resetTotalPoints() {
        $key = $this->makeKeyPointId();
        return $this->getClient()->executeRaw(['DEL', $key]);
    }

    protected function makeKeyPointId() {
        return static::STORAGE_NAME . static::SEPARATOR . static::KEY_POINT_ID;
    }

    protected function makeKeyPoint($id) {
        return static::STORAGE_NAME . static::SEPARATOR . static::KEY_POINT . static::SEPARATOR . $id;
    }
}