<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 18:49
 */
namespace backend\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord {

    public static $statuOptions=[-1=>'删除',0=>'隐藏',1=>'正常'];
    //指定数据表
    public static function tableName(){
        return 'article_category';
    }
    //验证规则
    public function rules(){
        return[
          [['name','intro','sort','status','is_help'],'required','message'=>'{attribute}不能为空'],//所有不能为空
            ['sort','match','pattern'=>'/^\d{1,9}$/','message'=>'排序号格式不正确'],//排序号只能为整数
        ];
    }
    public function attributeLabels(){
        return [
            'name'    => '分类名称',//标签名称
            'intro'    => '分类简介',//标签名称
            'sort'    => '分类排序',//标签名称
            'status'    => '分类状态',//标签名称
            'is_help'    => '分类类型',//标签名称
        ];
    }
}