<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/19
 * Time: 18:45
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\Order_goods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class FlowController extends Controller
{
    public $layout='header_dingdan';
    //支付方式
    public static $payments=[
        ['id'=>1,'name'=>'货到付款','info'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        ['id'=>2,'name'=>'在线支付','info'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        ['id'=>3,'name'=>'上门提取','info'=>'自提时付款，支持现金、POS刷卡、支票支付'],
        ['id'=>4,'name'=>'邮局汇款','info'=>'通过快钱平台收款 汇款后1-3个工作日到账']
    ];
    //发货方式
    public static $shippings=[
        ['id'=>1,'name'=>'普通快递送货上门','price'=>'10.00','info'=>'每张订单不满499.00元,运费15.00元, 订单4...'],
        ['id'=>2,'name'=>'特快专递','price'=>'40.00','info'=>'每张订单不满499.00元,运费40.00元, 订单4...'],
        ['id'=>3,'name'=>'加急快递送货上门','price'=>'40.00','info'=>'每张订单不满499.00元,运费40.00元, 订单4...'],
        ['id'=>4,'name'=>'平邮','price'=>'10.00','info'=>'每张订单不满499.00元,运费15.00元, 订单4...']
    ];
    public function actionFlow1(){
        if(\Yii::$app->user->isGuest) {//用户未登录
            //取出cookie中的商品id和数量
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                //cookie中没有购物车数据
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
        }else{//用户登录
            $nid=\Yii::$app->user->getId();
            //判断cookie中是否缓存有商品
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie){//判断cookie中缓存的商品
                $cart = unserialize($cookie->value);
                foreach ($cart as $goods_id => $amount) {
                    //判断cookie中缓存的商品 在数据库中是否有
                    $carts=Cart::findOne(['nid'=>$nid,'goods_id'=>$goods_id]);
                    if($carts){
                        $carts->nm +=$amount;//有 直接修改该类商品的数量
                        $carts->save();
                    }else{ //没有 添加
                        $car=new Cart();
                        $car->nid=$nid;
                        $car->goods_id=$goods_id;
                        $car->nm=$amount;
                        $car->save();
                    }
                }
                $coos = \Yii::$app->response->cookies;
                $coos->get('cart');
                $coos->remove('cart');
            }
            $carts=Cart::findAll(['nid'=>$nid]);
            $car=new Cart();
            $models = [];
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id' => $cart->goods_id])->attributes;
                $goods['amount'] = $cart->nm;
                $models[] = $goods;
            }
        }
        return $this->render('flow1',['models'=>$models]);
    }

    public function actionFlow2(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['flow/flow1']);
        }
        //获取用户id
        $nid=\Yii::$app->user->getId();
        //查询 用户地址
        $address=Address::find()->where(['nid'=>$nid])->orderBy('status DESC')->all();
        //发货方式
        $payments=self::$payments;
        //支付方式
        $shippings=self::$shippings;
        //获取该用户购物车中的商品数据
        $carts=Cart::findAll(['nid'=>$nid]);
        $count=Cart::find()->where(['nid'=>$nid])->count();
        $car=new Cart();
        $models = [];
        foreach ($carts as $cart) {
            $goods = Goods::findOne(['id' => $cart->goods_id])->attributes;
            $goods['amount'] = $cart->nm;
            $models[] = $goods;
        }
        return $this->render('flow2',['address'=>$address,'shippings'=>$shippings,'payments'=>$payments,'models'=>$models,'count'=>$count]);
    }

    public function actionFlow3(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['flow/flow1']);
        }
        return $this->render('flow3');
    }

    public function actionAdd(){
        //获取商品数量 商品id
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $update = \Yii::$app->request->post('update');
        $goods = Goods::findOne(['id'=>$goods_id]);

        if(\Yii::$app->user->isGuest){
            //未登录
            if($goods==null){
                throw new NotFoundHttpException('商品不存在');
            }
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookie中没有购物车数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
                //$cart = [2=>10];
            }

            //将商品id和数量存到cookie   id=2 amount=10  id=1 amount=3
            $cookies = \Yii::$app->response->cookies;
            /*$cart=[
                ['id'=>2,'amount'=>10],['id'=>1,'amount'=>3]
            ];*/
            //检查购物车中是否有该商品,有，数量累加
            if($update){
                if(key_exists($goods->id,$cart)){
                    $cart[$goods_id] = $amount;
                }
            }else{
                if($amount==0){
                    if($amount){
                        $cart[$goods_id] = $amount;
                    }else{
                        if(key_exists($goods['id'],$cart)) unset($cart[$goods_id]);
                    }
                }else{
                    if(key_exists($goods->id,$cart)){
                        $cart[$goods_id] += $amount;
                    }else{
                        $cart[$goods_id] = $amount;
                    }
                }
            }
//            $cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{//用户已登陆
            //获取用户id
            $nid=\Yii::$app->user->getId();
            //根据用户id 和商品id查询 购物车中是否有该商品
            $cart=Cart::findOne(['nid'=>$nid,'goods_id'=>$goods_id]);
            if($update){
                //当为登录状态时 此处的$goods_id 为购物车中的 id
                $cart=Cart::findOne(['id'=>$goods_id]);
                $cart->nm =$amount;
                $cart->save();
                return true;
            }
            if($amount==0){
                //当为登录状态时 此处的$goods_id 为购物车中的 id
                Cart::findOne(['id'=>$goods_id])->delete();
            }else{
                if($cart){
                    $cart->nm += $amount;
                    $cart->save();
                }else{
                    $carts=new Cart();
                    $carts->nid=$nid;
                    $carts->goods_id=$goods_id;
                    $carts->nm=$amount;
                    $carts->save();
                }
            }
        }
        return $this->redirect(['flow/flow1']);
    }

    public function actionOrder(){
        if(\Yii::$app->user->isGuest){//未登录
           return $this->redirect(['flow/flow1']);
        }
        $nid=\Yii::$app->user->getId();//获取用户id
        $addres=\Yii::$app->request->post('addres');
        $shipping=\Yii::$app->request->post('shipping');//配送方式
        //var_dump($shipping);
        $pay_select=\Yii::$app->request->post('pay_select');//支付方式
        $tfoot_all=\Yii::$app->request->post('tfoot_all');//总金额
        $order=new Order();
        $order->member_id=$nid;//用户id
        $address=Address::findOne(['id'=>$addres]);
        $order->name=$address['name'];//收货人
        $order->province=$address['province'];//省
        $order->city=$address['city'];//市
        $order->area=$address['district'];//县
        $order->address=$address['address'];//具体地址
        $order->tel=$address['tel'];//联系方式
        $order->delivery_id=$shipping;//发货方式id
        $shippings=self::$shippings;
        $order->delivery_name=$shippings[$shipping-1]['name'];//发货方式名称
        //var_dump($shippings[$shipping-1]);
        $order->delivery_price=$shippings[$shipping-1]['price'];//发货方式价格
        $order->payment_id=$pay_select;//支付方式id
        $payments=self::$payments;
        $order->payment_name=$payments[$pay_select-1]['name'];//支付方式名称
        $order->total=$tfoot_all;//支付总金额
        $order->status=0;//订单状态
        $order->trade_no=date('Ymd').rand(1000,9999);
        $order->create_time=time();//生成订单时间
        //开启事务
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $order->save();
            $carts=Cart::findAll(['nid'=>$nid]);
            //订单商品详情表
            foreach ($carts as $cart){
                $order_goods=new Order_goods();
                $order_goods->order_id=$order->id;
                $order_goods->goods_id=$cart->goods_id;
                $goods=Goods::findOne(['id'=>$cart->goods_id]);
                //商品数量
                if($goods==null){
                    throw new Exception($goods->name.'已下线！');
                }
                if($goods->stock<$cart->nm){
                    throw new Exception( $goods->name.'库存不足！');
                }
                $order_goods->goods_name=$goods->name;
                $order_goods->logo=$goods->logo;
                $order_goods->price=$goods->shop_price;
                $order_goods->amount=$cart->nm;
                $order_goods->total=$cart->nm*$goods->shop_price;
                //减库存
                $goods->stock=$goods->stock-$cart->nm;
                $goods->save();
                $order_goods->save();
            }
            //删除购物车中的商品
            Cart::deleteAll(['nid'=>$nid]);
            //提交
            $transaction->commit();
            return 'success';
        }catch (Exception $e){
            //回滚
            $transaction->rollBack();
            return 'false';
        }
    }

}