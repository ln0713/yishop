<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/18
 * Time: 11:06
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    public static function tableName(){
        return 'menu';
    }

    public function rules(){
        return [
            [['label','sort'],'required'],
            [['url'],'string','max'=>255],
            ['parent_id','safe']
        ];
    }

    public function attributeLabels(){
        return [
            'label'=>'菜单名称',
            'url'=>'菜单地址',
            'parent_id'=>'菜单父级',
            'sort'=>'菜单排序',
        ];
    }
    public function getChrild(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }
    //获取子菜单
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}