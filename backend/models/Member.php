<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/29
 * Time: 14:14
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Member extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return 'member';
    }

    public $password;//明文密码
    public $affirm_password;//明文密码
    public $code;//明文密码
    public $smsCode;//短信验证码
    public $agreement;
    public function rules()
    {
        return [
            //,'code','smsCode'
            [['username','password_hash','email'], 'required'],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            ['code','captcha','captchaAction'=>'api/captcha'],
            //自定义验证
            ['username','validateUsername'],
            //两次密码验证
            //[['affirm_password'], 'compare','compareAttribute'=>'password','message' => '两次密码不一致'],
            //验证是否同意
            //['agreement','safe'],
            //短信验证码
            //['smsCode','validateSms']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'affirm_password' => '确认密码',
            'code' => '验证码',
            'smsCode' => '短信验证码',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'email' => '邮箱',
            'tel' => '手机号码',
            'last_login_time' => '最近一次登录时间',
            'last_login_ip' => '最后一次登录IP',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
            'agreement' => '我已阅读并同意《用户注册协议》',
        ];
    }
    //自定义验证
    public function validateUsername()
    {
        $member = Member::findOne(['username' => $this->username]);
//        var_dump($user);exit;
        if ($member) {
            $this->addError('username', '用户名已存在');
        }
    }

    //验证短信验证码
    public function validateSms()
    {
        //缓存里面没有该电话号码
        $value = \Yii::$app->cache->get('tel_'.$this->tel);
        if(!$value || $this->smsCode != $value){
            $this->addError('smsCode','验证码不正确');
        }
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
            $this->status = 1;
            $this->auth_key = \Yii::$app->security->generateRandomString();
        }else{
            $this->updated_at = time();
        }
        if($this->password){
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
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
     * @return string|int an ID that uniquely identifies a user identity.
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
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
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
        // TODO: Implement validateAuthKey() method.
    }
}