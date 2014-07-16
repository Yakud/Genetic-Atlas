<?php
namespace tests\Atlas\Point;

use Atlas\Point\Point;
use Atlas\Point\PointRedisStorage;
use Atlas\Point\TestPointRedisStorage;
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
        $Point = $this->createPoint();
        $id = $this->getStorage()->save($Point);

        $this->assertEquals(1, $id);
    }

    public function testGetPoint() {
        $Point = $this->createPoint([
            'type' => 'test_type'
        ]);

        $Storage = $this->getStorage();
        $id = $Storage->save($Point);

        $PointFromStorage = $Storage->getPointById($id);

        $this->assertEquals($Point->getFieldValue('id'), $PointFromStorage->getFieldValue('id'));
        $this->assertEquals($Point->getFieldValue('type'), $PointFromStorage->getFieldValue('type'));
    }

    //////////////////////////////////////////////////////////////////
    protected $RedisStorage = null;

    /**
     * @return TestPointRedisStorage
     */
    protected function getStorage() {
        if (!$this->RedisStorage) {
            $this->RedisStorage = new TestPointRedisStorage();
        }

        return $this->RedisStorage;
    }

    /**
     * @param array $data
     * @return Point
     */
    protected function createPoint(array $data = array()) {
        return new Point($data);
    }
} 