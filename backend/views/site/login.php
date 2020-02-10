<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>后台用户登录</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="./css/font.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/xadmin.css">
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script src="./lib/layui/layui.js" charset="utf-8"></script>
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message">后台登录 Yii2 + Layui</div>
        <div id="darkbannerwrap"></div>

        <form method="post" class="layui-form" action="javascript:;">

            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken(); ?>">
            <div class="layui-form-item">
                <input name="LoginForm[username]" type="text" placeholder="用户名" lay-verify="required" class="layui-input">
            </div>

            <div class="layui-form-item">
                <input name="LoginForm[password]" type="password" placeholder="密码" lay-verify="required|check_pass" class="layui-input">
            </div>

            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">

            <hr>
            <a href="<?= \yii\helpers\Url::to(['site/forget']) ?>">忘记密码？</a>

        </form>
    </div>
    <script>
        $(function() {
            layui.use(['form'], function() {
                var form = layui.form;

                //自定义验证规则
                form.verify({
                    check_pass: [/^.{6,12}$/, '密码必须6到12位'],
                });

                //监听提交
                form.on('submit(login)', function(data) {
                    var load_index = layer.load(1);
                    $.ajax({
                        url: '<?= \yii\helpers\Url::to(['/site/do-login']); ?>',
                        type: 'post',
                        data: data.field,
                        dataType: 'json',
                        success: function(res) {
                            if (res.code == 'success') {
                                layer.msg(res.msg, {
                                    icon: 1
                                });
                                location.href = '<?= \yii\helpers\Url::to(['site/index']); ?>';
                            } else {
                                layer.msg(res.msg, {
                                    icon: 2
                                });
                            }
                        },
                        complete: function() {
                            layer.close(load_index)
                        }
                    });
                    return false;
                });
            });
        })
    </script>
    <!-- 底部结束 -->

</body>

</html>