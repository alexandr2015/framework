<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.16
 * Time: 14:11
 */

use core\controllers\Controller;
use application\models\Test;

class DefaultController extends Controller
{
    public function __construct()
    {
    }

    public function actionIndex()
    {
        $model = new Test();
        dd($model->tableName());
    }
}