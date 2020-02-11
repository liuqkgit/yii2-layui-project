<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property int $id ID
 * @property string $icon 图标
 * @property string $name 名称
 * @property string $controller 控制器
 * @property string $action action
 * @property int $level 等级
 * @property int $parent_id 父类id
 * @property int $sort 排序
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Menu extends \yii\db\ActiveRecord
{
    const SORT_DEFAULT = 99; //默认排序

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'parent_id', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['icon'], 'string', 'max' => 20],
            [['name', 'controller', 'action'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icon' => '图标',
            'name' => '名称',
            'controller' => '控制器',
            'action' => 'action',
            'level' => '等级',
            'parent_id' => '父类id',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 记录添加与修改时间
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
            }
            return true;
        } else {
            return false;
        }
    }
}
