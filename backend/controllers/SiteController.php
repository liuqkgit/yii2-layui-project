<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'do-login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'show'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * 
     * 左侧框架
     */
    public function actionIndex()
    {
        $this->layout = 'main';
        return $this->render('index');
    }

    /**
     * 登录后
     * 默认的【我的桌面】
     */
    public function actionShow(){
        $this->layout = 'new';
        return $this->render('show');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = false;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('login');
    }

    /**
     * 异步处理登录
     */
    public function actionDoLogin()
    {
        $model = new LoginForm();

        $model->load(Yii::$app->request->post());

        if ($model->login()) {
            return json_encode(['code' => 'success', 'msg' => '登录成功']);
        } else {
            $errors = $model->errors;

            $msg = '';
            if (is_array($errors)) {
                foreach ($errors as $key => $val) {
                    if (is_array($val)) {
                        foreach ($val as $index  => $item) {
                            $msg .= $item . '<br>';
                        }
                    } else {
                        $msg .= $val . '<br>';
                    }
                }
            } else {
                $msg .= $errors;
            }

            return json_encode(['code' => 'fail', 'msg' => $msg]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
