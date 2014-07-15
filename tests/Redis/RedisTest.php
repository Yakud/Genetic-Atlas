<?php
namespace tests\Redis;
use PHPUnit_Framework_TestCase;
use Redis\RedisClient;

/**
 *
 * @author yakud
 */
class RedisTest extends PHPUnit_Framework_TestCase {
    public function testSimple() {
        $Client = new RedisClient([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);

        $result = $Client->set('a', 100);
        $result = $Client->get('a');
        $result = $Client->get('b');

        $this->assertEquals(100, $result);
    }
} 