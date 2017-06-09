<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    public static $statuOptions=[-1=>'删除',0=>'隐藏',1=>'正常'];
    //指定数据表
    public static function tableName(){
        //brand表
        return 'brand';
    }
    //验证规则
    public function rules(){
        return [
          [['name','intro','sort','status'],'required','message'=>'{attribute}不能为空'],//所有不能为空
            ['sort','match','pattern'=>'/^\d{1,9}$/','message'=>'排序号格式不正确'],//排序号只能为整数
//            ['img','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],//指定图片的格式
            [['logo'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels(){
        return [
            'name'    => '品牌名称',//标签名称
            'intro'    => '品牌简介',//标签名称
            'logo'    => 'LOGO图片',//标签名称
            'sort'    => '品牌排序',//标签名称
            'status'    => '品牌状态',//标签名称
        ];
    }
}