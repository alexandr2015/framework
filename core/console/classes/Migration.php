<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.03.16
 * Time: 16:31
 */
class Migration
{
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
            . "namespace database\\migration;\n\n"
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
}