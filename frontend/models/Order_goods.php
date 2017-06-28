<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/25
 * Time: 15:20
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Order_goods extends ActiveRecord
{
    public static function tableName(){
        return 'order_goods';
    }
}