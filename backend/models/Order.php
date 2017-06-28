<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/26
 * Time: 9:54
 */

namespace backend\models;


use frontend\models\Member;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public static function tableName(){
        return 'order';
    }
    public function getProvinces(){
        return $this->hasOne(Locations::className(),['id'=>'province']);
    }
    public function getCitys(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }
    public function getAreas(){
        return $this->hasOne(Locations::className(),['id'=>'area']);
    }
    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }
}