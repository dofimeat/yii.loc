<?php

namespace app\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    public function actionHello()
    {
        return $this->render('hello');
    }

    public function actionIndex()
    {
        $param = \Yii::$app->request->get('param', 'Параметр не указан');

        return $this -> render('index', ['param' => $param]);
    }
}
