<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/12
 * Time: 8:34
 */

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;


class Goods extends ActiveRecord
{
    public static $statuOptions=[0=>'回收站',1=>'正常'];
    public static $saleOptions=[0=>'下架',1=>'在售'];
    public $sname;
    public $ssn;
    public static function tableName(){
        return 'goods';
    }
    public function rules(){
        //,'create_time'
        return[
            [['name','goods_category_id','brand_id', 'market_price','shop_price','stock','is_on_sale','status','sort'],
              'required','message'=>'{attribute}不能为空'],//所有不能为空
            [['logo'], 'string', 'max' => 255],
            [['sn'], 'string', 'max' => 255],
            [['sname'], 'string', 'max' => 255],
            ['sort','match','pattern'=>'/^\d{1,9}$/','message'=>'排序号格式不正确'],//排序号只能为整数
        ];
    }
    public function attributeLabels(){
        return [
            'name'    => '商品名称',//标签名称
            'logo'    => 'LOGO图片',//标签名称
            'goods_category_id'    => '商品分类ID',//标签名称
            'brand_id'    => '品牌分类',//标签名称
            'market_price'    => '市场价格',//标签名称
            'shop_price'    => '商品价格',//标签名称
            'stock'    => '库存',//标签名称
            'is_on_sale'    => '是否在售',//标签名称
            'status'    => '状态',//标签名称
            'sort'    => '排序',//标签名称
        ];
    }
    public function getGoods_category()
    {
        return $this->hasOne(Goods_category::className(),['id'=>'goods_category_id']);
    }
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    //搜索
    public function search($params)
    {
        //$query = Goods::find()->where(['is_on_sale'=>1])->orderBy('sort');
        $query = Goods::find()->orderBy('sort');
        //$query->joinWith(['brand']);//关联文章类别表
//         $query->joinWith(['author' => function($query) { $query->from(['author' => 'users']); }]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 6,
            ],
        ]);
        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//        $query->orFilterWhere(
//            ['like', 'name', $this->name]
//        );
        $query -> andFilterWhere(['like', 'name', $this->sname])//通过商品名模糊查询
               -> andFilterWhere(['like', 'sn', $this->sn]);//通过商品货号查询
        //$query->andFilterWhere(['like', 'cate.cname', $this->cname]) ;
        return $dataProvider;
    }

    /*
    * 商品和相册关系 1对多
    */
    public function getGalleries()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }
    /*
     * 获取商品详情
     */
    public function getGoodsIntro()
    {
        return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);
    }
}