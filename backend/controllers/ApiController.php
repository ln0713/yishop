<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/29
 * Time: 14:08
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\Category;
use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\Member;
use backend\models\Member_edit;
use backend\models\MemberloginForm;
use backend\models\Order;
use frontend\models\Address;
use frontend\models\Cart;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    public function init()
{
    \Yii::$app->response->format = Response::FORMAT_JSON;
    parent::init();
}
    //用户注册
    public function actionMemberRegister(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $member =new Member();
            $member->username = $request->post('username');
            $member->password_hash = $request->post('password_hash');
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            $member->code = $request->post('code');
            if($member->validate()){
                //加密密码
                $member->password_hash=\Yii::$app->security->generatePasswordHash($member->password_hash);
                $member->save();
                return ['status'=>'1','message'=>'','data'=>$member->toArray()];
            }
            //验证失败
            return ['status'=>'-1','message'=>$member->getErrors()];
        }
        return ['status'=>'-1','message'=>'请使用post请求'];
    }
    //用户登陆
    public function actionMemberLogin(){
        $request=\Yii::$app->request;
        $model=New MemberloginForm();
        if($request->isPost){
            $model->username = $request->post('username');
            $model->password_hash = $request->post('password_hash');
            $model->remember = $request->post('remember');
            if($model->validate()){
                //加密密码
                return ['status'=>'1','message'=>'','data'=>$model->username];
            }
            //验证失败
            return ['status'=>'-1','message'=>$model->getErrors()];
        }
        return ['status'=>'-1','message'=>'请使用post请求'];
    }
    public function actionMemberEdit(){
        $id=\Yii::$app->user->getId();
        if($id){
            $request=\Yii::$app->request;
            $model=New Member_edit();
            if($request->isPost){
                $model->old_password = $request->post('old_password');
                $model->new_password = $request->post('new_password');
                if($model->getPassword($id)){
                    $model->updated_at = time();
                    //加密密码
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                    return ['status'=>'1','message'=>'','data'=>$model->toArray()];
                }
                //验证失败
                return ['status'=>'-1','message'=>$model->getErrors()];
            }
            return ['status'=>'-1','message'=>'请使用post请求'];
        }
        return ['status'=>'-1','message'=>'请先登录请求'];
    }

    public function actionAddressAdd(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $address =new Address();
            $address->name = $request->post('name');
            $address->address = $request->post('address');
            $address->nid = $request->post('nid');
            $address->tel = $request->post('tel');
            $address->province = $request->post('province');
            $address->city = $request->post('city');
            $address->district = $request->post('district');
            if($address->validate()){
                $address->save();
                return ['status'=>'1','message'=>'','data'=>$address->toArray()];
            }
            //验证失败
            return ['status'=>'-1','message'=>$address->getErrors()];
        }
        return ['status'=>'-1','message'=>'请使用post请求'];
    }
    public function actionAddressIndex(){
        $id=\Yii::$app->user->getId();
        if($id){
            $address=Address::findAll(['nid'=>$id]);
            return ['status'=>'1','message'=>'','data'=>$address->toArray()];
        }
        return ['status'=>'-1','message'=>'请登录'];
    }
    public function actionAddressEdit(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $id = $request->post('id');
            $address =Address::findOne(['id'=>$id]);
            $address->name = $request->post('name');
            $address->address = $request->post('address');
            $address->nid = $request->post('nid');
            $address->tel = $request->post('tel');
            $address->province = $request->post('province');
            $address->city = $request->post('city');
            $address->district = $request->post('district');
            if($address->validate()){
                $address->save();
                return ['status'=>'1','message'=>'','data'=>$address->toArray()];
            }
            return ['status'=>'-1','message'=>$address->getErrors()];
        }
        return ['status'=>'-1','message'=>'请使用post请求'];
    }
    public function actionAddressDel(){
        $request=\Yii::$app->request;
        if($request->isGet) {
            $id = $request->get('id');
            Address::findOne(['id'=>$id])->delete();
            return ['status'=>'1','message'=>'','data'=>'删除成功'];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }

    public function actionGoodsCategoryIndex(){
        $goods_category=Goods_category::find()->asArray()->all();
        return ['status'=>'1','message'=>'','data'=>$goods_category];
    }
    public function actionGoodsCategoryChildren(){
        $request=\Yii::$app->request;
        if($request->isGet) {
            $id = $request->get('id');
            $goods_categories=Goods_category::find()->where(['parent_id'=>$id])->asArray()->all();
            return ['status'=>'1','message'=>'','data'=>$goods_categories];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }
    public function actionGoodsCategoryParent(){
        $request=\Yii::$app->request;
        if($request->isGet) {
            $id = $request->get('id');
            $category=Goods_category::findOne(['id'=>$id]);
            if($category){
                $goods_category=Goods_category::findOne(['id'=>$category->parent_id]);
                return ['status'=>'1','message'=>'','data'=>$goods_category];
            }
            return ['status'=>'-1','message'=>'参数错误'];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }

    public function actionCategoryGoodsIndex(){
        $request=\Yii::$app->request;
        if($request->isGet) {
            $goods_category_id = $request->get('goods_category_id');
            $goods=Goods::find()->where(['goods_category_id'=>$goods_category_id])->asArray()->all();
            return ['status'=>'1','message'=>'','data'=>$goods];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }
    public function actionBrandGoodsIndex(){
        $request=\Yii::$app->request;
        if($request->isGet) {
            $brand_id = $request->get('brand_id');
            $goods=Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
            return ['status'=>'1','message'=>'','data'=>$goods];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }

    public function actionCategoryIndex(){
        $category=Category::find()->asArray()->all();
        if($category){
            return ['status'=>'1','message'=>'','data'=>$category];
        }
        return ['status'=>'-1','message'=>'获取失败'];
    }
    public function actionCategoryArticleIndex(){
        $request=\Yii::$app->request;
        if($request->isGet){
            $category_id=$request->get('category_id');
            $articles=Article::find()->where(['article_category_id'=>$category_id])->asArray()->all();
            return ['status'=>'1','message'=>'','data'=>$articles];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }
    public function actionArticleCategory(){
        $request=\Yii::$app->request;
        if($request->isGet){
            $article_category_id=$request->get('article_category_id');
            $Category=Category::findone(['id'=>$article_category_id]);
            return ['status'=>'1','message'=>'','data'=>$Category];
        }
        return ['status'=>'-1','message'=>'请使用get请求'];
    }

    public function actionCartIndex(){

    }

    //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>3,
                'maxLength'=>3,
            ],
        ];
        //http://admin.yii2shop.com/api/captcha.html 显示验证码
        //http://admin.yii2shop.com/api/captcha.html?refresh=1 获取新验证码图片地址
        //http://admin.yii2shop.com/api/captcha.html?v=59573cbe28c58 新验证码图片地址
    }
    //文件上传
    public function actoionUpload(){
        //实例化文件上传对象
        $file=UploadedFile::getInstanceByName('img');
        if($file){
            //获取文件路径
            $fileName='/upload/'.uniqid().'.'.$file->extension;
            $res= $file->saveAs($fileName,false);
            if($res){
                return ['status'=>1,'msg'=>'文件上传成功！','data'=>$fileName];
            }
            return ['status'=>1,'msg'=>'文件上传失败！','data'=>$file->error];
        }
        return ['status'=>-1,'msg'=>'没有上传文件！','data'=>''];
    }


    //修改某商品的数量&&删除某件商品
    public function actionEditAmount(){

        $good_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
//        var_dump($good_id,$amount);exit;
        $goods=Goods::findOne(['id'=>$good_id]);
        if($goods==null){
            return ['status'=>-1,'msg'=>'没有此商品'];
        }
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){

            //现获取cookie中的数据
            $cookies= \Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');

            if($cookie==null){
                //cookie中没有商品
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);

            }
            //将数据保存在cookie中
            $cookies=\Yii::$app->response->cookies;
            //修改时，根据数量存在与否,进行删除
            if($amount){
                $cart[$good_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)) unset($cart[$good_id]);
            }

            $cookie=new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)

            ]);
            $cookies->add($cookie);
            return ['status'=>1,'msg'=>'存入cookie成功','data'=>$cart];
        }else{

            //用户已经登录，获取登录用户id
            $member_id=\Yii::$app->user->getId();

            //操作数据库
            $cart=Shopcart::findOne(['member_id'=>$member_id,'goods_id'=>$good_id]);
            if(\Yii::$app->request->isPost){

                if($amount==0){
                    Shopcart::findOne(['goods_id'=>$good_id])->delete();
                    return ['status'=>-1,'msg'=>'删除成功','data'=>$cart];
                }
                $cart->amount=$amount;
                $cart->save();
                return ['status'=>1,'msg'=>'累加成功','data'=>$cart];

            }


            return ['status'=>-1,'msg'=>'提交方式不正确'];
        }

    }

    //清除购物车
    public function actionDelCart(){
        $query=Cart::find();
        $total=$query->count();

        Cart::deleteAll();
        return ['status'=>1,'msg'=>'清除购物车成功！','data'=>$total];

    }

    //获取购物车的商品
    public function actionGoodsByCart(){

        $good_id=\Yii::$app->request->post('goods_id');
        $goods=Goods::findOne(['id'=>$good_id]);
        if($goods==null){
            return ['status'=>-1,'msg'=>'没有此商品'];
        }

        //判断用户是否登录
        if(\Yii::$app->user->isGuest) {
            //未登录就获取cookie的商品
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有商品
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);

            }
            $models = [];
            foreach ($cart as $good_id => $amount) {

                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;


            }
            return ['status' => -1, 'msg' => 'cookie中的购物信息', 'data' => $models];
        }else{

            //登录就获取当前用户的购物商品+cookie中的商品
            //如果登录了获取缓存数据，同步到数据库


        }


    }

    //订单
    //获取支付方式
    public function actionGetPayment(){
        //订单的操作都需要用户登录
        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }
        //登录后,获取用户id,获取订单信息
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();

        foreach ($orders as $order){

            $payment=  $order->payment_name;
        }
        return ['status'=>-1,'msg'=>'','data'=>$payment];

    }

    //获取支送货方式
    public function actionGetDelivery(){
        //订单的操作都需要用户登录
        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }
        //登录后,获取用户id,获取订单信息
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();

        foreach ($orders as $order){

            $delivery=  $order->delivery_name;
        }
        return ['status'=>-1,'msg'=>'','data'=>$delivery];

    }
    //提交订单
    public function actionPutOrder(){

        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }


    }

    //获取用户订单列表
    public function actionOrderList(){
        if(\Yii::$app->user->isGuest){

            return ['status'=>-1,'msg'=>'请先登录！'];
        }

        //登录后,获取用户id,获取订单信息
        $member_id=\Yii::$app->user->id;
        $orders=Order::find()->where(['member_id'=>$member_id])->all();


        return ['status'=>-1,'msg'=>'','data'=>$orders];

    }
}

