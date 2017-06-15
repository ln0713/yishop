<h1>相册</h1>
<div>
    <?php foreach ($imgs as $img):?>
    <div style="width: 25%;float: left;text-align: center;height: 260px">
        <div style="padding-top: 10px;background-color:#000000;padding-bottom: 10px;height: 240px;position：relative">
            <div><?php echo \yii\bootstrap\Html::img("/$img->img",['width'=>260]) ?></div>
            <div style="position:absolute bottom: 10px;">
                <?php echo \yii\bootstrap\Html::a('删除',['article/Imgdel?id='."$img->id"],['class'=>'btn btn-warning btn-xs']) ?>
                <?php echo \yii\bootstrap\Html::a('修改',['article/Imgedit?id='."$img->id"],['class'=>'btn btn-warning btn-xs']) ?>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<div style="clear: both"></div>
<?php
use yii\widgets\ActiveForm;
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>

<?= $form->field($model, 'file[]')->fileInput(['multiple' => true]) ?>
    <button>提交</button>
<?php ActiveForm::end(); ?>