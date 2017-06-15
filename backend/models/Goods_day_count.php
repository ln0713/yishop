<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/12
 * Time: 14:24
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Goods_day_count extends ActiveRecord
{
    public static function tableName(){
        return 'goods_day_count';
    }
}