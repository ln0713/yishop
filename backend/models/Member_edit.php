<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/29
 * Time: 14:14
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Member_edit extends ActiveRecord
{

    public static function tableName()
    {
        return 'member';
    }
    public $_password;//明文密码
    public $new_password;//明文密码
    public function rules()
    {
        return [
            //,'code','smsCode'
            [['old_password','new_password'], 'required'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'old_password' => '新密码',
            'new_password' => '新密码',
        ];
    }
    //自定义验证
    public function getPassword($id)
    {
        $member = Member::findOne(['id' =>$id]);
        if($member){
            if (!\Yii::$app->security->validatePassword($this->oldpassword,$member->password_hash)) {
                $this->addError('old_password', '旧密码不正确');
            }
        }
    }
}