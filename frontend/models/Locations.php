<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/20
 * Time: 20:11
 */

namespace frontend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Locations extends ActiveRecord
{
    public static function getRegion($parentId=0)
    {
        $result = static::find()->where(['parent_id'=>$parentId])->asArray()->all();
        return ArrayHelper::map($result, 'id', 'name');
    }
}