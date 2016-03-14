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
    public static function runQuery($query, $returnedData = false, $fetch = \PDO::FETCH_ASSOC)
    {
        $connection = Connection::getConnection();
        $query = $connection->prepare($query);
        $response = $query->execute();
        if ($returnedData) {
            return self::prepareResponse($query->fetchAll($fetch));
        }
        return $response;
    }

    private static function prepareResponse(array $response)
    {
        $result = [];
        foreach ($response as $key => $value) {
            foreach ($value as $field => $fieldValue) {
                $result[$field] = $fieldValue;
            }
        }

        return $result;
    }

    private function __construct() {}

    private function __clone() {}

    private function __sleep() {}

    private function __wakeup() {}
}