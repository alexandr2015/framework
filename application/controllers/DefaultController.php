<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.02.16
 * Time: 14:11
 */

use core\controllers\Controller;
use application\models\Test;
include '../../core/models/DatabaseModel.php';
class DefaultController extends Controller
{
    public function __construct()
    {
    }

    public function actionIndex()
    {
        $color = new DatabaseModel();
        $model = new Test();
        $res = $model->all();
        dd($res);
    }
}