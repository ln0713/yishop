<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/16
 * Time: 10:13
 */

namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model
{

    public $name;
    public $description;
    public function rules(){
        return [
            [['name','description'],'required','message'=>'{attribute}不能为空']
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'权限名称',
            'description'=>'权限描述',
        ];
    }
    //添加权限
    public function addPremission(){
        //实例化对象
        $authManager=\Yii::$app->authManager;
        //创建权限 （之前判断权限是否存在）
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            //保存数据
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;
            return $authManager->add($permission) ;
        }
        return false;
    }
    //修改权限
    public function editPermission($name){
        //实例化对象
        $authManager=\Yii::$app->authManager;
        //修改权限
        //通过name查询权限
        $permission=$authManager->getPermission($name);
        //判断 name 是否修改 修改后的name 是否等同已存在的name
        if($this->name !=$name && $authManager->getPermission($this->name)){//成立时
            $this->addError('name','权限已存在');
        }else{//不成立
            $permission->name=$this->name;
            $permission->description=$this->description;
            return $authManager->update($name,$permission) ;
        }
        return false;
    }
    public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }
}