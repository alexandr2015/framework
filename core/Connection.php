<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.03.16
 * Time: 16:04
 */
namespace core;

final class Connection
{
    private static $_dns;

    private static $_user;

    private static $_password;

    private static $_connection = false;

    private function __construct() {}

    public static function getConnection()
    {
        if (!self::$_connection) {
            self::createConnection();
        }
        return self::$_connection;
    }

    private static function createConnection()
    {
        $config = include \Config::get('config') . '/db.php';
        self::$_dns = $config['dns'];
        self::$_user = $config['user'];
        self::$_password = $config['password'];
        self::$_connection = new \PDO(self::$_dns, self::$_user, self::$_password);
    }

    private function __clone() {}

    private function __sleep() {}

    private function __wakeup() {}
}