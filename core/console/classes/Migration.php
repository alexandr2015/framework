<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.03.16
 * Time: 16:31
 */
use core\Db;

class Migration
{
    const MIGRATION_NAMESPACE = '\database\\migrations\\';

    public $run;
    public function __construct()
    {
        $this->createMigrationTable();
        $this->getMigrationFromDb();
    }

    public function actionUp()
    {
        $migrationDirectory = Config::get('root') . '/database/migrations';
        $files = scandir($migrationDirectory);
        foreach ($files as $file) {
            if (!in_array(
                    $file,
                    array_merge(
                        $this->run,
                        ['.', '..']
                    )
                )
            ) {
                $className = substr($file, 0, strlen($file) - 4);
                $class = self::MIGRATION_NAMESPACE . $className;
                $migrationObject = new $class;
                call_user_func_array([
                    $migrationObject,
                    'up'
                ], []);
                $queries = call_user_func_array([
                    $migrationObject,
                    'getAllQueries'
                ], []);
                foreach ($queries as $query) {
                    \core\Db::runQuery($query);
                }
                $this->insertCurrentMigrationToDb($className);
            }
        }
        return 'migration done';
    }

    public function actionDown()
    {
        $this->runMigrationMethod('down');
    }

    protected function runMigrationMethod($method)
    {
        $migrationDirectory = Config::get('root') . '/database/migrations';
        $files = scandir($migrationDirectory);
        foreach ($files as $file) {
            if (!in_array($file, ['.', '..'])) {
                $class = $file;
                $class = self::MIGRATION_NAMESPACE . substr($class, 0, strlen($class) - 4);
                $migrationObject = new $class;
                call_user_func_array([
                    $migrationObject,
                    $method
                ], []);
                $queries = call_user_func_array([
                    $migrationObject,
                    'getAllQueries'
                ], []);
                foreach ($queries as $query) {
                    \core\Db::runQuery($query);
                }
            }
        }
        return 'migration done';
    }

    public function actionCreate($name)
    {
        $className = 'migration' . date('Yms_His') . '_' . $name;
        $migrationName = Config::get('root'). '/database/migrations/' . $className . '.php';
        if (false !== file_put_contents($migrationName, $this->phpFile($className))) {
            return 'migration file successfully created';
        } else {
            return 'migration file successfully not created';
        }
    }

    public function phpFile($className)
    {
        $result = "<?php \n"
            . "namespace database\\migrations;\n\n"
            . "use core\\database\\migration\\BaseMigration;\n\n"
            . "class {$className} extends BaseMigration\n"
            . "{\n"
            . "    public function up()\n"
            . "    {\n\n"
            . "    }\n\n"
            . "    public function down()\n"
            . "    {\n\n"
            . "    }\n"
            . "}";

        return $result;
    }

    private function createMigrationTable()
    {
        $sql = 'create table if not exists migration (`name` VARCHAR(255) UNIQUE, date TIMESTAMP )';
        Db::runQuery($sql);
    }

    public function getMigrationFromDb()
    {
        $sql = 'SELECT name FROM migration';
        $this->run = Db::runQuery($sql, true);
    }

    public function insertCurrentMigrationToDb($migration)
    {
        $currentTimeStamp = date('Y-n-d H:i:s');
        $sql = "INSERT INTO migration (`name`, `date`) VALUES ('{$migration}', {$currentTimeStamp})";
        Db::runQuery($sql, true);
    }
}