<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/20
 * Time: 20:11
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Locations extends ActiveRecord
{
    public static function tableName(){
        return 'locations';
    }
}