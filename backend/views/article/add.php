<?php ;
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name')->textInput();//文章名称
echo $from->field($model,'intro')->textarea();//文章简介
echo $from->field($model,'text')->textarea();//文章详情
echo $from->field($model,'article_category_id')->textInput();//文章分类id
echo $from->field($model,'sort')->textInput();//文章排序号
echo $from->field($model,'status')->radioList(['-1'=>'删除',0=>'隐藏','1'=>'正常']);//文章状态
echo $from->field($model,'create_time')->textInput();//创建时间
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();