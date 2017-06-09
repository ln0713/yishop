<?php ;
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name')->textInput();//品牌名称
echo $from->field($model,'intro')->textarea();//品牌简介
echo $from->field($model,'img')->fileInput();//品牌图片
if($model->logo){echo "<img src='$model->logo' width='80px' /> ";};//判断品牌图片是否存在值
echo $from->field($model,'sort')->textInput();//品牌排序号
echo $from->field($model,'status')->radioList(['-1'=>'删除',0=>'隐藏','1'=>'正常']);//品牌状态
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();