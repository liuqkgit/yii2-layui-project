<?php

use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">

                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-normal" onclick="xadmin.open('添加菜单','<?php echo Url::to(['menu/create', 'pid' => $this_parent_id]) ?>',800,600)">
                        添加
                    </button>
                </div>

                <div class="layui-card-body ">

                    <table id="dataTable" lay-filter="tableBar"></table>
                    <script type="text/html" id="options">
                        <div class="layui-btn-group">
                            <button type="button" class="layui-btn layui-btn-sm layui-btn-warm" lay-event="son">子级列表</button>
                            <button type="button" class="layui-btn layui-btn-sm" lay-event="edit">编辑</button>
                            <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" lay-event="del">删除</button>
                        </div>
                    </script>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    layui.use('table', function() {
        var table = layui.table;
        var form = layui.form,
            layer = layui.layer;
        //第一个实例
        var tableObj = table.render({
            elem: '#dataTable',
            // height: 700,
            url: "<?= Url::to(['menu/index-data']); ?>", //数据接口
            where: {
                parent_id: '<?php echo $this_parent_id ?>'
            },
            page: true, //开启分页
            limit: 20,
            limits: [20, 40, 50],
            id: 'tableReload',
            cols: [
                [ //表头
                    {
                        field: 'id',
                        title: 'ID',
                        sort: true,
                        width: 80,
                    }, {
                        field: 'name',
                        title: '名称',
                    }, {
                        title: '图标',
                        templet: function(d) {
                            return '<i class="iconfont">' + d.icon + '</i>'
                        },
                    }, {
                        field: 'sort',
                        title: '排序',
                        edit: 'text',
                    }, {
                        field: 'created_at',
                        title: '添加时间'
                    }, {
                        field: 'updated_at',
                        title: '修改时间'
                    }, {
                        fixed: 'right',
                        title: '操作',
                        width: 200,
                        align: 'center',
                        toolbar: '#options'
                    }
                ]
            ],
            response: {
                statusName: 'code', //规定数据状态的字段名称，默认：code
                statusCode: 200, //规定成功的状态码，默认：0
                msgName: 'msg', //规定状态信息的字段名称，默认：msg
                countName: 'count', //规定数据总数的字段名称，默认：count
                dataName: 'data' //规定数据列表的字段名称，默认：data
            }
        });

        table.on('tool(tableBar)', function(obj) { //注：tool是工具条事件名， lay-filter="对应的值"
            var data = obj.data; //获得当前行数据

            var layEvent = obj.event; //获得 lay-event 对应的值

            if (layEvent === 'edit') {
                xadmin.open('编辑', '<?= Url::to(['menu/create']); ?>' + '&id=' + data.id, 800, 600)
            } else if (layEvent === 'son') {
                xadmin.open('子级列表', '<?= Url::to(['menu/index']); ?>' + '&pid=' + data.id)
            } else if (layEvent === 'del') {

                layer.confirm('确认删除吗？', {
                    title: '数据删除',
                    icon: 3
                }, function(index) {
                    layer.close(index);

                    var load_index = layer.load(1)
                    $.ajax({
                        url: '<?= Url::to(['menu/delete']); ?>',
                        type: 'post',
                        data: {
                            id: data.id,
                            "<?= Yii::$app->request->csrfParam; ?>": "<?= Yii::$app->request->getCsrfToken(); ?>"
                        },
                        dataType: 'json',
                        success: (res) => {
                            if (res.code == 'success') {
                                obj.del(); //删除对应行（tr）的DOM结构
                                layer.msg(res.msg, {
                                    icon: 1,
                                    time: 1000
                                });
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
            };
        })

        //监听单元格编辑
        table.on('edit(tableBar)', function(obj) {
            var value = obj.value, //得到修改后的值
                data = obj.data; //得到所在行所有键值

            if (value < 0 || value > 9999) {
                layer.msg('排序范围在0~9999之间')
                return false;
            }

            var load_index = layer.load(1)
            $.ajax({
                url: '<?= Url::to(['/ad/set-sort']); ?>',
                type: 'post',
                data: {
                    id: data.id,
                    sort: value,
                    _csrf: csrfToken
                },
                dataType: 'json',
                success: (res) => {
                    if (res.code == 'success') {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000
                        });
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

    })
</script>