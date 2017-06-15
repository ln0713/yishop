<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $code;
    public $remember;

    public function rules(){
        return[
            [['username','password_hash','code'], 'required','message'=>'{attribute}不能为空'],//不能为空的字段
            //验证码验证
            ['code','captcha','captchaAction'=>'login/captcha'],
            [['remember'], 'string', 'max' => 10],
            //添加自定义验证
            ['username','validateUsername'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'remember'=>'是否记住账号密码',
        ];
    }
    //
    public function validateUsername(){
        //获取用户信息
        $name= User::findOne(['username'=>$this->username]);
        //判断用户信息是否存在
        if($name){//存在
            //用户存在 验证密码
            if(!\Yii::$app->security->validatePassword($this->password_hash,$name->password_hash)){
                $this->addError('password_hash','密码不正确');
            }else{
                $time=$this->remember?7*24*3600:0;
                //账号秘密正确，登录
                \Yii::$app->user->login($name,$time);
            }
        }else{//不存在时
            $this->addError('username','账号不正确');
        }
    }
}