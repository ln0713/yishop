<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/16
 * Time: 10:07
 */

namespace backend\controllers;


use backend\models\PermissionForm;
use backend\models\RoleForm;
use backend\models\UserForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\filters\AccessFilter;

class RbacController extends PassController
{
    //权限列表
    public function actionIndexpermission(){
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
        return $this->render('index-permission',['permissions'=>$permissions]);
    }
    //添加权限
    public function actionAddpermission(){
        $model=new PermissionForm();
        if($model->load(\Yii::$app->request->post())  && $model->validate()){
            if($model->addPremission()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect(['rbac/indexpermission']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //权限删除
    public function actionDelpermission($name){
        //通过name查询权限
        $permission=\Yii::$app->authManager->getPermission($name);
        //判断查询结果
        if($permission==null){//查询结果等于null时 提示错误
            throw new NotFoundHttpException('权限不存在');
        }
        //查询结果不等于null时 删除权限
        \Yii::$app->authManager->remove($permission);
        //删除成功后提示删除成功
        \Yii::$app->session->setFlash('success','权限删除成功');
        //返回首页
        return $this->redirect(['rbac/indexpermission']);
    }
    //权限修改
    public function actionEditpermission($name){
        //实例化对象
        $model=new PermissionForm();
        //通过name查找
        $permission=\Yii::$app->authManager->getPermission($name);
        if($permission==null){//判断查询结果不存在时
            throw  new NotFoundHttpException('权限不存在');
        }
        $model->loadData($permission);
        //判断请求方式
        if($model->load(\Yii::$app->request->post())  && $model->validate()){
            if($model->editPermission($name)){
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['rbac/indexpermission']);
            }
        }else{
            return $this->render('add-permission',['model'=>$model]);
        }
    }
    //添加角色
    public function actionAddrole(){
        $model=new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect(['rbac/indexrole']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //角色列表
    public function actionIndexrole(){
        $authManager=\Yii::$app->authManager;
        $roles=$authManager->getRoles();
        return $this->render('index-role',['roles'=>$roles]);
    }
    //删除角色
    public function actionDelrole($name){
        //通过name查询权限
        $role=\Yii::$app->authManager->getRole($name);
        //判断查询结果
        if($role==null){//查询结果等于null时 提示错误
            throw new NotFoundHttpException('角色不存在');
        }
        //查询结果不等于null时 删除角色的权限
        \Yii::$app->authManager->remove($role);
        //删除成功后提示删除成功
        \Yii::$app->session->setFlash('success','角色删除成功');
        //返回首页
        return $this->redirect(['rbac/indexrole']);
    }
    //修改角色
    public function actionEditrole($name){
        $role = \Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $model = new RoleForm();
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->editRole($name)){
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['rbac/indexrole']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //用户表关联
    public function actionAdduser($id){
        $model=new UserForm();

        $users=\Yii::$app->authManager->getAssignments($id);
        //判断用户是否关联过角色
        if($users){
            //$model->loadData($users,$id);
            $model->loadData($users);
        }else{
            $model->name=$id;
        }
            if($model->load(\Yii::$app->request->post())  && $model->validate()){
                if($model->addUser()){
                    \Yii::$app->session->setFlash('success','用户绑定角色成功');
                    return $this->redirect(['user/index']);
                }
            }
        return $this->render('add-user',['model'=>$model]);
    }
    //删除 用户角色
    public function actionDeluser($id){
        echo $id;exit;
        $authManager = \Yii::$app->authManager;
        $authManager->revokeAll($id);
        \Yii::$app->session->setFlash('success','用户全部角色清除成功');
        return $this->redirect(['user/index']);

    }
}