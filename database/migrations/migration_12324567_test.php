<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10.03.16
 * Time: 21:31
 */
namespace database\migration;

use core\database\migration\BaseMigration;

class migration_12324567_test extends BaseMigration
{
    protected function up()
    {
        $this->createTable('Jora', [
            'id' => $this->integer()->default()
        ]);
    }

    protected function down()
    {
        $this->dropTable('Jora');
    }
}