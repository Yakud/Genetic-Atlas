<?php
namespace tests\Atlas\Point;

use Atlas\Point\Point;
use Atlas\Point\PointRedisStorage;
use PHPUnit_Framework_TestCase;

/**
 *
 * @author yakud
 */
class PointRedisStorageTest extends PHPUnit_Framework_TestCase {
    protected function setUp() {
        $Storage = $this->getStorage();
        $Storage->resetTotalPoints();
    }

    public function testSimple() {
        $Storage = $this->getStorage();
        $this->assertInstanceOf('Atlas\\Point\\PointRedisStorage', $Storage);
    }

    public function testSavePoint() {
        $Point = $this->getPoint([
            'id'   => '500',
            'type' => 'test_type',
        ]);

        $Storage = $this->getStorage();
        $id = $Storage->save($Point);

        $Point = $Storage->getPointById($id);
        var_export($Point);
    }

    //////////////////////////////////////////////////////////////////
    /**
     * @var PointRedisStorage
     */
    protected $RedisStorage = null;

    /**
     * @return PointRedisStorage
     */
    protected function getStorage() {
        if ($this->RedisStorage) {
            return $this->RedisStorage;
        }

        $this->RedisStorage = new PointRedisStorage();
        return $this->RedisStorage;
    }

    /**
     * @param array $data
     * @return Point
     */
    protected function getPoint(array $data = array()) {
        return new Point($data);
    }
} 