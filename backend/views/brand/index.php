<table class="table">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>LOGO图片</th>
        <th>品牌排序</th>
        <th>品牌状态</th>
        <th>图书操作</th>
    </tr>
    <?php foreach ($brand as $brands):?>
        <tr>
            <td><?=$brands->id?></td>
            <td><?=$brands->name?></td>
            <td><?=$brands->intro?></td>
            <td><?php echo \yii\bootstrap\Html::img("$brands->logo",['width'=>80]) ?></td>
            <td><?=$brands->sort?></td>
            <td><?php echo \backend\models\Brand::$statuOptions[$brands->status]  ?></td>
            <td><?php echo \yii\bootstrap\Html::a('删除',['brand/del?id='."$brands->id"],['class'=>'btn btn-warning btn-xs']) ?>
                <?php echo \yii\bootstrap\Html::a('修改',['brand/edit?id='."$brands->id"],['class'=>'btn btn-warning btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-danger']);
?>
<div></div>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

]);
