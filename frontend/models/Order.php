<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/25
 * Time: 15:20
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName(){
        return 'order';
    }
    public static $statuOptions=[0=>'未支付',1=>'已完成',2=>'已取消',3=>'待收货'];
    public function getAddress($address){
        return Order::findOne(['id'=>$address]);
    }
    public function getGoods(){
        return $this->hasMany(Goods::className(),['order_id'=>'id']);
    }
}