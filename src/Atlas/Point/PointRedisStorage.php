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
            $pointId = $this->incrementNextPointId();
            $Point->import(['id' => $pointId]);
        }

        $data = $Point->export();
        $data = json_encode($data);

        $storageKey = $this->makePointKey($pointId);
        $this->getClient()->executeRaw(['SET', $storageKey, $data]);

        return $pointId;
    }

    /**
     * @param int $id
     * @return Point|null
     */
    public function getPointById($id) {
        $storageKey = $this->makePointKey($id);

        $data = $this->getClient()->executeRaw(['GET', $storageKey]);
        if ($data) {
            $data = json_decode($data, true);
            return new Point($data);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getStorageConfig() {
        $config = parent::getStorageConfig();

        $config['database'] = self::STORAGE_NAME;
        $config['prefix']   = self::STORAGE_NAME;
        return $config;
    }

    protected function makePointKey($id) {
        return self::KEY_POINT . self::SEPARATOR . $id;
    }

    protected function incrementNextPointId() {
        return $this->getClient()->executeRaw(['INCR', self::KEY_POINT_ID]);
    }

    public function resetTotalPoints() {
        return $this->getClient()->executeRaw(['DEL', self::KEY_POINT_ID]);
    }
}