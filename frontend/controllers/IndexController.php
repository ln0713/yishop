<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/19
 * Time: 15:24
 */

namespace frontend\controllers;


use backend\models\Goods_category;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout = 'index';
    public function actionIndex(){
        return $this->render('index');
    }
}