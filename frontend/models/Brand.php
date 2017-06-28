<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/21
 * Time: 16:45
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    public static function tableName(){
        return 'brand';
    }
}