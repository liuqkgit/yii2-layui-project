<?php

namespace backend\controllers;

use common\models\Menu;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ReturnMsg;

class MenuController extends Controller
{
    public $layout = 'new';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // 'actions' => ['logout', 'index', 'show'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(), //配置指定action可以响应哪些请求方式
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 列表页
     */
    public function actionIndex()
    {
        $this_parent_id = Yii::$app->request->get('pid');
        return $this->render('index', [
            'this_parent_id' => $this_parent_id
        ]);
    }

    /**
     * 列表页的数据接口
     */
    public function actionIndexData()
    {
        $page = Yii::$app->request->get('page') ?: 1; //页码

        $limit = Yii::$app->request->get('limit') ?: 20; //每页数量

        $parent_id = Yii::$app->request->get('parent_id') ?: 0; //父级id

        $model = Menu::find()->where(['parent_id' => $parent_id]);

        $dataCount = $model->count();

        $totalPage = $dataCount / $limit; // 可翻页数

        if (ceil($totalPage) < $page) {
            return ReturnMsg::LayuiReturn(false, ['code' => 204, 'msg' => '已加载全部数据', 'count' => $dataCount, 'data' => []]);
        }

        $offset = ($page - 1) * $limit;

        $model = $model
            ->offset($offset)
            ->limit($limit)
            ->all();

        $data = yii\helpers\ArrayHelper::toArray($model, [
            'common\models\Menu' => [
                'id',
                'icon',
                'name',
                'sort' => function ($son) {
                    return $son->sort == Menu::SORT_DEFAULT ? '' : $son->sort;
                },
                'created_at' => function ($son) {
                    return date('Y-m-d H:i:s', $son->created_at);
                },
                'updated_at' => function ($son) {
                    return date('Y-m-d H:i:s', $son->updated_at);
                }
            ]
        ]);

        return ReturnMsg::LayuiReturn(true, ['msg' => 'ok', 'count' => $dataCount, 'data' => $data]);
    }

    /**
     * 编辑页面
     */
    public function actionCreate()
    {
        $id = Yii::$app->request->get('id');
        $pid = Yii::$app->request->get('pid') ?: 0;

        $model = Menu::findOne($id);
        if (!$model) $model = new Menu();

        $pModel = Menu::findOne($pid);
        if (!$pModel) {
            if ($model) {
                $pid = $model->parent_id;
            } else {
                $pid = 0;
            }
        }

        return $this->render('create', [
            'model' => $model,
            'pid' => $pid,
        ]);
    }

    /**
     * 保存数据
     */
    public function actionDoCreate()
    {
        $request = Yii::$app->request;

        $id = $request->post('id');
        $name = $request->post('name');
        $icon = $request->post('icon');
        $controller = $request->post('controller');
        $action = $request->post('action');
        $parent_id = $request->post('parent_id');
        $sort = $request->post('sort');

        if (!$name) return ReturnMsg::ReturnMsg(false, ['msg' => '请输入名称']);

        $sort = intval($sort);

        $model = Menu::findOne($id);
        if (!$model) {
            $model = new Menu();
        }

        $parent_level = 0;
        $parent_model = Menu::findOne($parent_id);
        if ($parent_model) {
            $parent_level = $parent_model->level;
        }

        $model->icon = $icon ?: '';
        $model->name = $name;
        $model->controller = $controller ?: '';
        $model->action = $action ?: '';
        $model->level = $parent_level + 1;
        $model->parent_id = $parent_id ?: 0;
        $model->sort = $sort ?: Menu::SORT_DEFAULT;

        if (!$model->save()) return ReturnMsg::ReturnMsg(false, ['msg' => '保存失败']);

        return ReturnMsg::ReturnMsg(true, ['msg' => '保存成功']);
    }

    /**
     * 删除数据
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = Menu::findOne($id);
        if (!$model) return ReturnMsg::ReturnMsg(false, ['msg' => '无效的数据']);
        if (!$model->delete()) return ReturnMsg::ReturnMsg(false, ['msg' => '删除失败']);
        return ReturnMsg::ReturnMsg(true, ['msg' => '删除成功']);
    }
}
