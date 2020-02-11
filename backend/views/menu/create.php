<?php

use common\models\Menu;
?>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">

            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken(); ?>">

            <div class="layui-form-item">
                <label for="name" class="layui-form-label">名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" value="<?= $model->name ?>" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="icon" class="layui-form-label">图标</label>
                <div class="layui-input-inline">
                    <input type="text" id="icon" name="icon" value="<?= $model->icon ?>" autocomplete="off" class="layui-input iconfont">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="controller" class="layui-form-label">控制器</label>
                <div class="layui-input-inline">
                    <input type="text" id="controller" name="controller" value="<?= $model->controller ?>" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="action" class="layui-form-label">Action</label>
                <div class="layui-input-inline">
                    <input type="text" id="action" name="action" value="<?= $model->action ?>" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="sort" class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="number" min="0" id="sort" name="sort" value="<?= (!$model->sort || $model->sort == Menu::SORT_DEFAULT) ? '' : $model->sort ?>" autocomplete="off" class="layui-input">
                </div>
            </div>

            <input type="hidden" name="id" value="<?= $model->id ?: '0'; ?>">
            <input type="hidden" name="parent_id" value="<?= $pid; ?>">

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button class="layui-btn" lay-filter="add" lay-submit="">
                    保存
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    layui.use(['form', 'layer'], function() {
        $ = layui.jquery;
        var form = layui.form,
            layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data) {
            var load_index = layer.load(1)
            $.ajax({
                url: '<?= yii\helpers\Url::to(['/menu/do-create']); ?>',
                type: 'post',
                data: data.field,
                dataType: 'json',
                success: function(res) {
                    if (res.code == 'success') {
                        layer.msg(res.msg, {
                            icon: 1
                        })

                        setTimeout(() => {
                            xadmin.close()
                            // xadmin.father_reload() //刷新整个父级页面
                            parent.layui.table.reload('tableReload', {
                                page: {
                                    curr: 1
                                }
                            }); //刷新父级表格
                        }, 1500)
                    } else {
                        layer.msg(res.msg, {
                            icon: 2
                        });
                    }
                },
                complete: () => {
                    layer.close(load_index)
                }
            });
            return false;
        });

    });
</script>