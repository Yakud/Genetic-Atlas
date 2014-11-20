<?php
namespace Atlas\Marker;

use Lib\Storage\RedisStorage;

/**
 *
 * @author akiselev
 */
class MarkerRedisStorage extends RedisStorage {
    const INDEX_KEY = 'all';

    /**
     * @param Marker $Marker
     * @return \Atlas\Marker\Marker
     */
    public function save(Marker $Marker) {
        if (!$Marker->get('id')) {
            $id = $this->incrementId();
            $Marker->set('id', $id);
        }

        $data     = $Marker->export();
        $jsonData = json_encode($data);

        $key = $this->makeMarkerKey($Marker->get('id'));
        $indexKey = $this->makeIndexKey(self::INDEX_KEY);

        $this->executeRaw(['SET', $key, $jsonData]);
        $this->executeRaw(['RPUSH', $indexKey, $Marker->get('id')]);

        return $Marker;
    }

    /**
     * @param $id
     * @return Marker
     * @throws \Exception
     */
    public function getById($id) {
        $key = $this->makeMarkerKey($id);

        $jsonData = $this->executeRaw(['GET', $key]);
        if (is_null($jsonData)) {
            throw new \Exception();
        }

        $data = json_decode($jsonData, true);
        $Marker = new Marker($data);

        return $Marker;
    }

    /**
     * @param int[] $ids
     * @return Marker[]
     */
    public function getByIds(array $ids) {
        $Markers = [];
        foreach ($ids as $id) {
            try {
                $Marker = $this->getById($id);
            } catch (\Exception $Ex) {
                continue;
            }

            $Markers[] = $Marker;
        }

        return $Markers;
    }

    /**
     * @param int $start
     * @param int $length
     * @return int[]
     */
    public function getIds($start, $length = null) {
        if ($length === null) {
            $length = $this->totalMarkers();
        }

        $indexKey = $this->makeIndexKey(self::INDEX_KEY);

        return $this->executeRaw(['LRANGE', $indexKey, $start, $start + $length - 1]);
    }

    /**
     * @return int
     */
    public function incrementId() {
        return (int) $this->executeRaw(['INCR', $this->makeIdKey()]);
    }

    /**
     * @return int
     */
    public function getLastId() {
        $key = $this->makeIdKey();
        return (int) $this->executeRaw(['GET', $key]);
    }

    /**
     * @return int
     */
    public function totalMarkers() {
        $indexKey = $this->makeIndexKey(self::INDEX_KEY);
        return (int) $this->executeRaw(['LLEN', $indexKey]);
    }

    public function clearStorage() {
        $ids = $this->getIds(0);
        foreach ($ids as $id) {
            $key = $this->makeMarkerKey($id);
            $this->executeRaw(['DEL', $key]);
        }

        $key = $this->makeIndexKey(self::INDEX_KEY);
        $this->executeRaw(['DEL', $key]);

        $key = $this->makeIdKey();
        $this->executeRaw(['DEL', $key]);
    }

    /**
     * @param int $id
     * @return string
     */
    protected function makeMarkerKey($id) {
        return "atlas:marker:{$id}";
    }

    protected function makeIdKey() {
        return "atlas:marker_id";
    }

    protected function makeIndexKey($index) {
        return "atlas:marker:index:{$index}";
    }
} 