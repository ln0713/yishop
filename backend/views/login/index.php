<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/5
 * Time: 11:54
 */
$form =\yii\bootstrap\ActiveForm::begin();//表单开始
echo $form->field($model,'username')->textInput();//用户名
echo $form->field($model,'password_hash')->passwordInput();//密码
echo $form->field($model,'remember')->checkbox();//是否记住账号密码
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'login/captcha',
   'template'=>'<div class="row"><div class="col-xs-2">{input}</div><div class="col-xs-2">{image}</div></div>'
]);
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();//表单结束