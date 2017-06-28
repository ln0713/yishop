<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/14
 * Time: 19:01
 */

namespace backend\controllers;


use backend\filters\AccessFilter;
use backend\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;

class PassController extends Controller
{
//验证是否登给其权限
    public function behaviors(){

        //使用RBAC过滤器
        return[
            'accessFilter' =>[
                'class'=>AccessFilter::className(),
                'nocheckactions'=>[
                    //截取路由中的控制器
                    strstr((\Yii::$app->request->pathInfo),'/',true).'/s-upload',
                ]
            ]
        ];
    }

}

