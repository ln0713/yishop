<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/26
 * Time: 9:50
 */

namespace backend\controllers;

use backend\models\Order;
use backend\models\Order_goods;

class OrderController extends PassController
{
    //订单列表
    public function actionIndex(){
        $orders = Order::find()->all();
        return $this->render('index',['orders'=>$orders]);

    }
    //订单处理
    public function actionUpdate($id){
        $orders = Order::findOne(['id'=>$id]);
        if($orders){
            $orders->status=1;
            $orders->save();
        }
        \Yii::$app->session->setFlash('success','发货成功');
        return $this->redirect(['order/index']);
    }
    public function actionOrder_goods($order_id){
        $order_goodss=Order_goods::findAll(['order_id'=>$order_id]);
        return $this->render('order_goods',['order_goodss'=>$order_goodss]);
    }
}