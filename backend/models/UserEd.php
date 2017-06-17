<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/14
 * Time: 11:16
 */

namespace backend\models;

use yii\db\ActiveRecord;

class UserEd extends ActiveRecord
{
    public $two_password;
    public $code;
    public $old_password;
    public $new_password;
    public static $statuOptions=[0=>'禁用',1=>'正常'];
    public static function tableName(){
        return 'user';
    }
    public function rules(){
        return[
            [['username','email','status','img'],'required','message'=>'{attribute}不能为空'],//不能为空
//            ['img','safe'],
            //[['img'], 'string', 'max' => 255],
            [['old_password','new_password','two_password'],'string', 'max' => 16],
            //邮箱
            ['email', 'email'],
            //添加自定义验证
            ['username','validatePassword'],
            //两次密码验证
            [['two_password'], 'compare','compareAttribute'=>'new_password','message' => '两次密码不一致'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'old_password'=>'旧密码',
            'new_password'=>'旧密码',
            'two_password'=>'再一次输入密码',
            'img' => '头像',
            'email'=>'邮箱',
            'status'=>'状态',
            'created_at'=>'创建时间',
            'updated_at'=>'更新时间',
            'last_at'=>'最近登陆时间',
            'last_ip'=>'最后登录ip',
        ];
    }
    //自定义的面膜验证
    public function validatePassword(){
        //通过是否填写旧密码来判断是否要修改密码
        if($this->old_password){
            $user = User::findOne(['username'=>$this->username]);
            //验证输入的旧密码是否一致
            if(md5($this->old_password) !=$user->password_hash){
                $this->addError('old_password','输入的旧密码不正确');
            }else if($this->old_password==$this->new_password){
                $this->addError('new_password','新密码与旧密码相同');
            }
        }

    }
}