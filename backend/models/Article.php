<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 18:49
 */
namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord {

    public static $statuOptions=[-1=>'删除',0=>'隐藏',1=>'正常'];
    public $text;
    //指定数据表
    public static function tableName(){
        return 'article';
    }
    //验证规则
    public function rules(){
        return[
          [['name','intro','text','article_category_id','sort','status','create_time'],'required','message'=>'{attribute}不能为空'],//所有不能为空
            ['sort','match','pattern'=>'/^\d{1,9}$/','message'=>'排序号格式不正确'],//排序号只能为整数
        ];
    }
    public function attributeLabels(){
        return [
            'name'    => '文章名称',//标签名称
            'intro'    => '文章简介',//标签名称
            'text'    => '文章详情',//标签名称
            'article_category_id'    => '文章分类id',//标签名称
            'sort'    => '文章排序',//标签名称
            'status'    => '文章状态',//标签名称
            'create_time'    => '创建时间',//标签名称
        ];
    }
    public function getCategory()
    {
        //hasOne的第二个参数【k=>v】 k代表商品分类在分类中对应的id  v代表分类的id
        return $this->hasOne(Category::className(),['id'=>'article_category_id']);
    }
}