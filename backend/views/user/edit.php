<?php ;
use yii\web\JsExpression;
use xj\uploadify\Uploadify;

$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'username')->textInput(['readonly'=>'true']);//品牌名称
echo $from->field($model,'old_password')->passwordInput();//品牌简介
echo $from->field($model,'new_password')->passwordInput();//品牌简介
echo $from->field($model,'two_password')->passwordInput();//品牌简介
echo $from->field($model,'img')->hiddenInput();//品牌图片
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址(data.fileUrl)写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功后的图片地址(data.fileUrl)写入img字段
        $("#usered-img").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->img){
    echo \yii\helpers\Html::img($model->img,['height'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
}

echo $from->field($model,'email')->textInput();//品牌排序号
echo $from->field($model,'status')->radioList([0=>'禁用','1'=>'正常']);//品牌状态
echo $from->field($model,'role',['inline'=>true])->checkboxList(\backend\models\UserEd::getRolesOptions());//用户角色
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();