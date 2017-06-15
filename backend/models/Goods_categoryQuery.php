<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/11
 * Time: 14:25
 */

namespace backend\models;


use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class Goods_categoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}