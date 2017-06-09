<table class="table">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>文章分类id</th>
        <th>文章排序</th>
        <th>文章状态</th>
        <th>创建时间</th>
        <th>文章操作</th>
    </tr>
    <?php foreach ($article as $articles):?>
        <tr>
            <td><?=$articles->id?></td>
            <td><?=$articles->name?></td>
            <td><?=$articles->intro?></td>
            <td><?=$articles->category->name?></td>
            <td><?=$articles->sort?></td>
            <td><?php echo \backend\models\Brand::$statuOptions[$articles->status]  ?></td>
            <td><?=$articles->create_time?></td>
            <td><?php echo \yii\bootstrap\Html::a('删除',['article/del?id='."$articles->id"],['class'=>'btn btn-warning btn-xs']) ?>
                <?php echo \yii\bootstrap\Html::a('修改',['article/edit?id='."$articles->id"],['class'=>'btn btn-warning btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\bootstrap\Html::a('添加品牌',['article/add'],['class'=>'btn btn-danger']);
?>
<div></div>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',

]);
