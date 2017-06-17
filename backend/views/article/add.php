<?php ;
$from=\yii\bootstrap\ActiveForm::begin();
//echo $from->field($model, 'text')->textInput();
echo $from->field($model,'name')->textInput();//文章名称
echo $from->field($model,'intro')->textarea();//文章简介
//if(@$detail){
//    echo $from->field($model, 'text')->widget(\crazyfd\ueditor\Ueditor::className(),['value'=>$detail->content]);//文章详情
//}else{
//    echo $from->field($model, 'text')->widget(\crazyfd\ueditor\Ueditor::className(),[]);//文章详情
//}
echo $from->field($detail,'content')->widget(\crazyfd\ueditor\Ueditor::className(),[]);//文章详情
echo $from->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($category,'id','name'),['prompt'=>'请选择文章分类']);//文章分类id
echo $from->field($model,'sort')->textInput();//文章排序号
echo $from->field($model,'status')->radioList(['-1'=>'删除',0=>'隐藏','1'=>'正常']);//文章状态
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();