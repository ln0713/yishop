<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['readonly'=>'true']);//权限名称
echo $form->field($model,'role',['inline'=>true])->checkboxList(\backend\models\UserForm::getRoleOptions());
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
echo '&nbsp;&nbsp;&nbsp;';//'rbac/adduser?id='."$user->id"
//echo \yii\bootstrap\Html::a('清除全部绑定角色',['rbac/deluser?id='],['class'=>'btn btn-danger']);
$form=\yii\bootstrap\ActiveForm::end();