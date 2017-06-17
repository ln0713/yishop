<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 18:49
 */
namespace backend\models;

use yii\db\ActiveRecord;

class Detail extends ActiveRecord {

    //指定数据表
    public static function tableName(){
        return 'article_detail';
    }
    public function rules(){
        return [
            ['content','string']
        ];
    }
    public function attributeLabels(){
        return [
            'article_id'=>'Id',
            'content'=>'文章详情',
        ];
    }
    public function getDetail($id){
        return $detail = Detail::findOne(['article_id'=>$id]);
    }
}