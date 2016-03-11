<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.03.16
 * Time: 19:26
 */
namespace core;

final class Db
{
    public static function runQuery($query, $returnedData = false)
    {
        $connection = Connection::getConnection();
        $query = $connection->prepare($query);
        $response = $query->execute();
        if ($returnedData) {
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $response;
    }
    private function __construct() {}

    private function __clone() {}

    private function __sleep() {}

    private function __wakeup() {}
}