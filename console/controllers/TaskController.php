<?php
namespace console\controllers;

use frontend\models\Order;
use yii\console\Controller;

class TaskController extends Controller{
    public function actionClean(){
        set_time_limit(0);//不限制脚本执行时间
        while (1){
            $orders=Order::find()->where(['status'=>0])->andWhere(['<','create_time',time()-3600])->all();
            foreach ($orders as $order){
                            $order->status=2;
                            $order->save();
                            foreach ($order->goods as $goods){
                                \Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
                            }
                echo $order->id.'come over all !';
            }
            sleep(2);
        }
    }
}