<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/21
 * Time: 23:29
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Img extends ActiveRecord
{
    public static function tableName(){
        return 'img';
    }
}