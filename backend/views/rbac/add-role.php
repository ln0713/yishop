<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//权限名称
echo $form->field($model,'description')->textarea();//权限描述
//角色的权限
echo $form->field($model,'permissions',['inline'=>true])->checkboxList(\backend\models\RoleForm::getPermissionOptions());
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();