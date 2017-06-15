<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/12
 * Time: 16:36
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Goods_intro extends ActiveRecord
{
    public static function tableName(){
        return 'goods_intro';
    }
    public function rules()
    {
        return [
            [['content'],'required','message'=>'{attribute}不能为空']
        ];
    }
    public function attributeLabels(){
        return [
            'content'    => '商品详情',//标签名称
        ];
    }
}