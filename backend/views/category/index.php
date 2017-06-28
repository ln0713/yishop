<table class="table">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>分类简介</th>
        <th>分类排序</th>
        <th>分类状态</th>
        <th>分类类型</th>
        <th>分类操作</th>
    </tr>
    <?php foreach ($category as $categorys):?>
    <tr>
        <td><?=$categorys->id?></td>
        <td><?=$categorys->name?></td>
        <td><?=$categorys->intro?></td>
        <td><?=$categorys->sort?></td>
        <td><?php echo \backend\models\Brand::$statuOptions[$categorys->status]  ?></td>
        <td><?=$categorys->is_help?></td>
        <td><?php if (Yii::$app->user->can('category/del')) {
                echo \yii\bootstrap\Html::a('删除',['category/del','id'=>$categorys->id],['class'=>'btn btn-warning btn-xs']);} ?>
            <?php if (Yii::$app->user->can('category/edit')) {
                echo \yii\bootstrap\Html::a('修改',['category/edit','id'=>$categorys->id],['class'=>'btn btn-warning btn-xs']);} ?>
        </td>
    </tr>
<?php endforeach;?>
    </table>
<?php if (Yii::$app->user->can('category/add')) {
echo \yii\bootstrap\Html::a('添加类型',['category/add'],['class'=>'btn btn-danger']);}
?>
    <div></div>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

]);
