<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/9
 * Time: 19:01
 */
namespace backend\models;


use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class Goods_category extends ActiveRecord
{
    //指定数据表
    public static function tableName(){
        return 'goods_category';
    }

    //验证规则
    public function rules(){
        return[
            [['name','intro'],'required','message'=>'{attribute}不能为空'],//所有不能为空
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
            //分类名称不能重复
            ['name','unique'],
        ];
    }
    public function attributeLabels(){
        return [
            'id' =>'ID',
            'tree'    => '树id',//标签名称
            'lft'    => '左值',//标签名称
            'rgt'    => '右值',//标签名称
            'depth'    => '层级',//标签名称
            'name'    => '名称',//标签名称
            'parent_id'    => '上级分类id ',//标签名称
            'intro'    => '简介',//标签名称
        ];
    }
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new Goods_categoryQuery(get_called_class());
    }
    public function getParent()
    {
        //hasOne的第二个参数【k=>v】
        return $this->hasOne(Goods_category::className(),['id'=>'parent_id']);
    }
}