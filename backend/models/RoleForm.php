<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/16
 * Time: 14:43
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions;

    public function rules(){
        return [
          [['name','description'],'required','message'=>'{attribute}不能为空'],
            ['permissions','safe']
        ];
    }
    public function attributeLabels(){
        return [
          'name'=>'名称',
          'description'=>'描述',
          'permissions'=>'权限',
        ];
    }

    //获取所有权限选项
    public static function getPermissionOptions()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');//获取所有权限
    }
    //添加角色
    public function addRole(){
        //实例化对象
        $authManager=\Yii::$app->authManager;
        //创建权限 （之前判断权限是否存在）
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }else{
            //保存数据
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            if($authManager->add($role)){
                foreach ($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){$authManager->addChild($role,$permission);}
                }
                return true;
            }
        }
        return false;
    }
    //修改角色
    public function editRole($name){
        //实例化对象
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //如果角色名被修改，检查修改后的名称是否已存在
        if($name != $this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名称已存在');

        }else{
            //给角色赋值
            $role->name = $this->name;
            $role->description = $this->description;
            if($authManager->update($name,$role)){
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }
    //从角色中加载数据
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //权限赋值
        $permissions= \Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }
}