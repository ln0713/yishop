<?php ;
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name')->textInput();//分类名称
echo $from->field($model,'intro')->textarea();//分类简介
echo $from->field($model,'sort')->textInput();//分类排序号
echo $from->field($model,'status')->radioList(['-1'=>'删除',0=>'隐藏','1'=>'正常']);//分类状态
echo $from->field($model,'is_help')->textInput();//分类类型
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();