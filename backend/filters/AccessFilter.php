<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\HttpException;

class AccessFilter extends ActionFilter{
    public $nocheckactions;
//$action->controller->id.'/s-upload';

    public function beforeAction($action){
        //获取当前路由
        //$action->uniqueId;
        //获取控制器
        //$action->controller->id;
        $user = \Yii::$app->user;
        //排除上传图片的方法
//        $action->controller->id.'/s-upload';
        //判断当前用户是否拥有操作权限
        if(!in_array($action->uniqueId,$this->nocheckactions)){
            if(! $user->can($action->uniqueId)){
                if($user->isGuest){
                    return $action->controller->redirect($user->loginUrl);
                }
                //没有权限 抛出异常
                throw new  HttpException(403,'对不起你没有权限访问该页面');
            }
        }

        return parent::beforeAction($action);
    }
}