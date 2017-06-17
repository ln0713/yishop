<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($permissions as $permission): ?>
        <tr>
            <td><?=$permission->name?></td>
            <td><?=$permission->description?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('删除',['rbac/delpermission?name='."$permission->name"],['class'=>'btn btn-warning btn-xs'])?>
                <?php echo \yii\bootstrap\Html::a('修改',['rbac/editpermission?name='."$permission->name"],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>