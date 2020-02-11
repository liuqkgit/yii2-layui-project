<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\models\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);

$menus = Menu::find()->where(['level' => "1"])->all();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="x-admin-sm">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <!-- <title>后台登录-X-admin2.2</title> -->
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="./css/font.css">
    <link rel="stylesheet" href="./css/xadmin.css">
    <!-- <link rel="stylesheet" href="./css/theme5.css"> -->
    <script src="./lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>
    <?php $this->head() ?>

</head>

<body class="index">
    <?php $this->beginBody() ?>

    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo">
            <a href="./index.html">X-admin v2.2</a></div>
        <div class="left_open">
            <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
        </div>
        <ul class="layui-nav left fast-add" lay-filter="">
            <li class="layui-nav-item">
                <a href="javascript:;">+新增</a>
                <dl class="layui-nav-child">
                    <!-- 二级菜单 -->
                    <dd>
                        <a onclick="xadmin.open('最大化','http://www.baidu.com','','',true)">
                            <i class="iconfont">&#xe6a2;</i>弹出最大化</a></dd>
                    <dd>
                        <a onclick="xadmin.open('弹出自动宽高','http://www.baidu.com')">
                            <i class="iconfont">&#xe6a8;</i>弹出自动宽高</a></dd>
                    <dd>
                        <a onclick="xadmin.open('弹出指定宽高','http://www.baidu.com',500,300)">
                            <i class="iconfont">&#xe6a8;</i>弹出指定宽高</a></dd>
                    <dd>
                        <a onclick="xadmin.add_tab('在tab打开','member-list.html')">
                            <i class="iconfont">&#xe6b8;</i>在tab打开</a></dd>
                    <dd>
                        <a onclick="xadmin.add_tab('在tab打开刷新','member-del.html',true)">
                            <i class="iconfont">&#xe6b8;</i>在tab打开刷新</a></dd>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav right" lay-filter="">
            <li class="layui-nav-item">
                <a href="javascript:;">admin</a>
                <dl class="layui-nav-child">
                    <!-- 二级菜单 -->
                    <dd>
                        <a onclick="xadmin.open('个人信息','http://www.baidu.com')">个人信息</a></dd>
                    <dd>
                        <a onclick="xadmin.open('切换帐号','http://www.baidu.com')">切换帐号</a></dd>
                    <dd>
                        <a href="javascript:;" onclick="logout()">退出</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item to-index">
                <a href="/">前台首页</a></li>
        </ul>
    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
    <!-- 左侧菜单开始 -->
    <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <?php foreach ($menus as $val) : ?>

                    <li>
                        <a href="javascript:;">
                            <i class="iconfont left-nav-li" lay-tips="<?php echo $val->name ?>"><?php echo $val->icon ?></i>
                            <cite><?php echo $val->name ?></cite>
                            <i class="iconfont nav_right">&#xe697;</i>
                        </a>

                        <?php $son_menus = Menu::find()->where(['and', ['=', 'parent_id', $val->id]])->orderBy('id ASC')->all(); ?>

                        <?php if ($son_menus) : ?>
                            <ul class="sub-menu">
                                <?php foreach ($son_menus as $son_val) : ?>
                                    <li>
                                        <a onclick="xadmin.add_tab('<?php echo $son_val->name ?>','<?php echo Url::to([$son_val->controller . '/' . $son_val->action]) ?>')">
                                            <i class="iconfont"><?php echo $son_val->icon ?></i>
                                            <cite><?php echo $son_val->name ?></cite>
                                        </a>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>

                    </li>

                <?php endforeach ?>

            </ul>
        </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
            <ul class="layui-tab-title">
                <li class="home">
                    <i class="layui-icon">&#xe68e;</i>我的桌面</li>
            </ul>
            <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                <dl>
                    <dd data-type="this">关闭当前</dd>
                    <dd data-type="other">关闭其它</dd>
                    <dd data-type="all">关闭全部</dd>
                </dl>
            </div>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe src='<?php echo Url::to(['site/show']) ?>' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
                </div>
            </div>
            <div id="tab_show"></div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <style id="theme_style"></style>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->

    <?php $this->endBody() ?>

    <script>
        function logout() {
            layer.confirm('确认要退出吗？', {
                icon: 7,
                title: '退出提示',
                cancel: function(index) { //点击右上角 X 关闭
                    layer.close(index)
                }
            }, function(index) { //点击确认
                location.href = "<?php echo Url::to(['site/logout']) ?>"
            }, function(index) { //点击取消
                layer.close(index)
            })
        }
    </script>
</body>

</html>
<?php $this->endPage() ?>