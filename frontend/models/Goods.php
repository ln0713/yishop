<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/21
 * Time: 17:21
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    public static function tableName(){
        return 'goods';
    }
}