<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/14
 * Time: 19:01
 */

namespace backend\controllers;


use backend\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;

class PassController extends Controller
{
//验证是否登给其权限
    public function behaviors(){

        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>['index','add','edit','del'],
                'rules'=>[
                    [
                        'allow'=>true,
                        'actions'=>[''],
                        'roles'=>['?'],
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['index','add','edit','del'],
                        'roles'=>['@'],
                    ],
                ],
            ],
        ];
    }
}

