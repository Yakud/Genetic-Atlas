<?php
namespace tests\Atlas\Point;

use Atlas\Point\Point;
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

    public function testDeletePoint() {
        $Point[0] = $this->createPoint();
        $Point[1] = $this->createPoint();

        $Storage = $this->getStorage();
        $Storage->save($Point[0]);
        $Storage->save($Point[1]);

        $id = $Point[0]->getFieldValue('id');
        $PointFromStorage = $Storage->getPointById($id);
        $this->assertEquals($id, $PointFromStorage->getFieldValue('id'));

        $Storage->delete($Point[0]);
        $PointFromStorage = $Storage->getPointById($id);
        $this->assertNull($PointFromStorage);

        $PointFromStorage = $Storage->getPointById($Point[1]->getFieldValue('id'));
        $this->assertEquals($Point[1]->getFieldValue('id'), $PointFromStorage->getFieldValue('id'));
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