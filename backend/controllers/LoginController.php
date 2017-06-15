<?php
namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use frontend\models\Account;
use yii\web\Controller;
use yii\web\Request;

class LoginController extends Controller
{

    //判断当前用户是否已登录
//    public function actionUser()
//    {
//        //实例化user组件
//        $user = \Yii::$app->user;
//        //var_dump($user->id);exit;
//        //获取当前登录用户实例(如果当前用户已登录)
//        var_dump($user->identity);
//        //获取当前登录用户的id
//        var_dump($user->id);
//        //判断当前用户是否是游客（未登录）
//        var_dump($user->isGuest);
//    }
    public function actionIndex(){
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //跳转到登录检测页
                $user=User::findOne(['username'=>$model->username]);
                $last_ip=$request->userIP;
                $user->last_ip=$last_ip;
                $user->last_at=time();
                var_dump($request->auth_key);exit;
                $user->auth_key=time();
                $user->save(false);
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('index',['model'=>$model]);
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>5,
            ],
        ];
    }
    //退出 注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login/index']);
    }
}