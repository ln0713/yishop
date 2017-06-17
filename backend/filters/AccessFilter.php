<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\HttpException;

class AccessFilter extends ActionFilter{
    public function beforeAction($action){
        //获取当前路由
        $action->uniqueId;
        //判断当前用户是否拥有操作权限
        if(! \Yii::$app->user->can($action->uniqueId)){
            if(\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl);
            }
            //没有权限 抛出异常
            throw new  HttpException(403,'对不起你没有权限访问该页面');
        }
        return parent::beforeAction($action);
    }
}