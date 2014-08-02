<?php
namespace Connection;
use Config\Config;
use mysqli;
use PDO;
use Traits\Singleton;

/**
 * Фасад для работы с подклчениями (напрмер к БД)
 * @author akiselev
 */
class ConnectionFacade {
    /**
     * @var PDO
     */
    protected static $Mysql = null;

    /**
     * Возвращает PDO подключение к mysql
     * @return PDO
     */
    public static function getMysql() {
        if (is_null(static::$Mysql)) {
            $config = Config::getInstance()->get('mysql');

            static::$Mysql = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password']
            );
        }

        return static::$Mysql;
    }
} 