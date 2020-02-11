<?php

namespace backend\controllers;

use yii\web\Controller;

class IconController extends Controller{

    public function actionIndex()
    {
        $this->layout = 'new';
        return $this->render('index');
    }
}