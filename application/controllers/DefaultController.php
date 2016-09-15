<?php

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
        $res = $model->asArray()->all();
        dd($res);
    }
}