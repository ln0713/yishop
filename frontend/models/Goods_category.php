<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/21
 * Time: 14:34
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Goods_category extends ActiveRecord
{
    public static function tableName(){
        return 'goods_category';
    }
//    public function getChildren($id){
//       return $goods_categorys=$this::find()->where(['parent_id'=>$id])->all();
//    }
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}