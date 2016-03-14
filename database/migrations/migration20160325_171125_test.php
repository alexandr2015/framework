<?php 
namespace database\migrations;

use core\database\migration\BaseMigration;

class migration20160325_171125_test extends BaseMigration
{
    public function up()
    {
        $this->createTable('Test2', [
            'id' => 'int not null PRIMARY KEY',
            'name' => 'varchar(255) not null',
        ]);
    }

    public function down()
    {
        $this->dropTable('Test2');
    }
}