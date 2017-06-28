<table class="table">
    <tr>
        <th>ID</th>
        <th>菜单名称</th>
        <th>url地址</th>
        <th>所需菜单</th>
        <th>菜单排序</th>
        <th>菜单操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?=$menu->id?></td>
            <td><?=$menu->label?></td>
            <td><?=$menu->url?></td>
            <td><?=$menu->parent_id==0? '一级菜单' : $menu->chrild->label ?></td>
            <td><?=$menu->sort?></td>
            <td><?php if (Yii::$app->user->can('menu/del')) {
                    echo \yii\bootstrap\Html::a('删除',['menu/del','id'=>$menu->id],['class'=>'btn btn-warning btn-xs']);} ?>
                <?php if (Yii::$app->user->can('menu/edit')) {
                    echo \yii\bootstrap\Html::a('修改',['menu/edit','id'=>$menu->id],['class'=>'btn btn-warning btn-xs']);} ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php if (Yii::$app->user->can('menu/add')) {
echo \yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-danger']);}
?>
<div></div>

<?php
//分页工具条
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$page,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页',
//
//]);
