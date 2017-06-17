<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/16
 * Time: 16:35
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserForm extends Model
{
    public $role;
    public $name;
    public function rules(){
        return [
            [['name'],'required','message'=>'{attribute}不能为空'],
            ['role','safe']
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'名称',
            'role'=>'角色',
        ];
    }
    //获取所有角色选项
    public static function getRoleOptions()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','name');//获取所有权限
    }
    //添加用户角色
    public function addUser()
    {
        //实例化对象
        $authManager = \Yii::$app->authManager;

        //用即将关联角色的用户id查询 是否关联有角色
        $models=$authManager->getAssignments($this->name);
        if($models){//true  清除该 id 原关联角色
            $authManager->revokeAll($this->name);
        }//false 直接关联

        //选取的角色是否为空
        if(!$this->role==null){
            //获取角色
            foreach ($this->role as $role){
                $roles=$authManager->getRole($role);
                $authManager->assign($roles,$this->name);
            }
        }
        return true;
    }
    public function editUser($model){
        var_dump($model);exit;
    }
    //从角色中加载数据
    public function loadData($users){
        foreach ($users as $user){
            $id=$user->userId;
            $roles[]=$user->roleName;
        }
        $this->name=$id;
        //权限赋值
        foreach ($roles as $role){
            $this->role[]=$role;
        }
    }
}