<?php ;
use yii\web\JsExpression;
use xj\uploadify\Uploadify;

$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'label')->textInput();//菜单名称
echo $from->field($model,'url')->textInput();//菜单url地址
echo $from->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($menus,'id','label'),['prompt'=>'一级菜单']);//菜单父级
echo $from->field($model,'sort')->textInput();//菜单排序
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();