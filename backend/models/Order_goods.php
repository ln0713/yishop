<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/26
 * Time: 9:54
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Order_goods extends ActiveRecord
{
    public static function tableName(){
        return 'order_goods';
    }
    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
}