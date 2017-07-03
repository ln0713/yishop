<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/19
 * Time: 15:24
 */

namespace frontend\controllers;


use backend\models\Goods_category;
use frontend\components\SphinxClient;
use frontend\models\Goods;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class IndexController extends Controller
{
    public $layout = 'index';
    public function actionIndex(){
        return $this->render('index');
    }
    public function actionTest(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '华为';//需要搜索的值
        $res = $cl->Query($info, 'goods');//shopstore_search
        var_dump($res);
        //return $this->render('test',['']);
    }
    public function actionGoods(){
        echo 1;
    }

}