<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/14
 * Time: 11:16
 */

namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $two_password;
    public $code;
    public $role;
    public static $statuOptions=[0=>'禁用',1=>'正常'];
    public static function tableName(){
        return 'user';
    }

    public function rules(){
        return[
            [['username','password_hash','two_password','email','status'],'required','message'=>'{attribute}不能为空'],//不能为空
            [['img'], 'string', 'max' => 255],
            //juese
            ['role','safe'],
            //邮箱
            ['email', 'email'],
            ['email', 'validateEmail'],
            //添加自定义验证
            ['username','validateUsername'],
            //两次密码验证
            [['two_password'], 'compare','compareAttribute'=>'password_hash','message' => '两次密码不一致'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'two_password'=>'再一次输入密码',
            'img' => '头像',
            'email'=>'邮箱',
            'status'=>'状态',
            'created_at'=>'创建时间',
            'updated_at'=>'更新时间',
            'last_at'=>'最近登陆时间',
            'last_ip'=>'最后登录ip',
            'role'=>'角色',
        ];
    }
    //自定义验证
    public function validateUsername()
    {
        $user = User::findOne(['username' => $this->username]);
//        var_dump($user);exit;
        if ($user) {
                $this->addError('username', '用户名已存在');
        }
    }
    public function validateEmail()
    {
        $user = User::findOne(['email' => $this->email]);
        if ($user) {
            if ($this->email == $user->email) {
                $this->addError('email', '用户名已存该邮箱已被使用');
            }
        }
    }
    public static function getRolesOptions(){
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();//因为获取出来是对象
        //所以需要返回成一个数组
        return ArrayHelper::map($authManager->getRoles(),'name','name');
    }

    //添加角色
    public function addRole($id){
        //根据传过来的用户id  给他添加角色
        $authManager = \Yii::$app->authManager;
        //获取表单提交的角色
        $roles  = $authManager->getRole($this->role);
//        var_dump($this->role);exit;
        //遍历所有选中的角色
        foreach($this->role as $roleName){
            $role=$authManager->getRole($roleName);
            //将角色分配给用户
            $authManager->assign($role,$id);
        }
        return true;
    }


    //修改角色
    public function editRole($id){
        $authManager = \Yii::$app->authManager;
        //删除该用户的所有角色
        $authManager->revokeAll($id);
        //再把表单提交的角色赋值给该用户
        //遍历所有选中的角色
        foreach($this->role as $roleName){
            $role=$authManager->getRole($roleName);
            //将角色分配给用户
            $authManager->assign($role,$id);
        }
        return true;


    }

    //获取数据 回显
    public function getData($id){
        //根据id获取该用户所有角色
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
//        var_dump($roles);exit;
        //遍历角色 把所有角色放进表单的数组里
        foreach($roles as $role){
            $this->role[] = $role->name;
        }
        return true;

    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
        // TODO: Implement findIdentity() method.
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
        // TODO: Implement getId() method.
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */


    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}