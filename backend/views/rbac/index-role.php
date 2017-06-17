<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role): ?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td><?php
                $permissions=Yii::$app->authManager->getPermissionsByRole($role->name);
                foreach ($permissions as $permission)
                    echo $permission->description.'&nbsp;&nbsp;';
                ?>
            </td>
            <td>
                <?php echo \yii\bootstrap\Html::a('删除',['rbac/delrole?name='."$role->name"],['class'=>'btn btn-warning btn-xs'])?>
                <?php echo \yii\bootstrap\Html::a('修改',['rbac/editrole?name='."$role->name"],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>