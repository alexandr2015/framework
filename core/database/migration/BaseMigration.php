<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10.03.16
 * Time: 21:18
 */
namespace core\database\migration;

abstract class BaseMigration
{
    use MigrationBuilder;

    abstract protected function up();

    abstract protected function down();
}
