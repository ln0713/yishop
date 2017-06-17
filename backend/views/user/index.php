<h1>用户列表</h1>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>头像</th>
        <th>email</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>最近登陆时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td><?php echo \yii\bootstrap\Html::img("$user->img",['width'=>80]) ?></td>
            <td><?=$user->email?></td>
            <td><?php echo \backend\models\User::$statuOptions[$user->status]  ?></td>
            <td><?=$user->created_at?></td>
            <td><?=$user->updated_at?></td>
            <td><?=$user->last_at?></td>
            <td><?=$user->last_ip?></td>
            <td><?php echo \yii\bootstrap\Html::a('删除',['user/del?id='."$user->id"],['class'=>'btn btn-warning btn-xs']) ?>
                <?php echo \yii\bootstrap\Html::a('修改',['user/edit?id='."$user->id"],['class'=>'btn btn-warning btn-xs']) ?>
                <?php echo \yii\bootstrap\Html::a('权限管理',['rbac/adduser?id='."$user->id"],['class'=>'btn btn-warning btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\bootstrap\Html::a('账号注册',['user/add'],['class'=>'btn btn-danger']);
?>
<div></div>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'firstPageLabel'=>"首页",
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页',
    'lastPageLabel'=>'尾页',

]);
