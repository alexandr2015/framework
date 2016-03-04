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
        $res = $model->select(['name', 'smth'])
            ->where('name', 5)
            ->andWhere('name', 6)
            ->where('name', '!=', 8)
            ->where('name', 7)
            ->where('smth', 'bla')
            ->where('smth', 'blas')
            ->orWhere('smth', 'dsf');
        var_dump($res); die;
    }
}