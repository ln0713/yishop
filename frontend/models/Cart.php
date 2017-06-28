<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/23
 * Time: 16:26
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public $logo;
    public $name;
    public $market_price;
    public $shop_price;
    public $amount;
    public static function tableName(){
        return 'cart';
    }
    public function GetGoods($goods_id){
        return Goods::findOne(['id'=>$goods_id]);
    }
}