<table class="table table-bordered">
    <thead>
        <tr>
            <th>名称</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $permission): ?>
            <tr>
                <td><?=$permission->name?></td>
                <td><?=$permission->description?></td>
                <td>
                    <?php if (Yii::$app->user->can('rbac/delpermission')) {
                        echo \yii\bootstrap\Html::a('删除',['rbac/delpermission','name'=>$permission->name],['class'=>'btn btn-warning btn-xs']);}?>
                    <?php if (Yii::$app->user->can('rbac/editpermission')) {
                        echo \yii\bootstrap\Html::a('修改',['rbac/editpermission','name'=>$permission->name],['class'=>'btn btn-danger btn-xs']);}?>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if (Yii::$app->user->can('rbac/addpermission')) {
    echo \yii\bootstrap\Html::a('添加',['rbac/addpermission'],['class'=>'btn btn-warning btn-xs']);}?>

<?php
/**
 * @var $this \yii\web\View
 */
//$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerCssFile('/css/jquery.dataTables.min.css');
//$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
"oLanguage" : {
        "sLengthMenu": "每页显示 _MENU_ 条记录",
        "sZeroRecords": "抱歉， 没有找到",
        "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
        "sInfoEmpty": "没有数据",
        "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
        "sZeroRecords": "没有检索到数据",
         "sSearch": "搜索:",
        "oPaginate": {
        "sFirst": "首页",
        "sPrevious": "前一页",
        "sNext": "后一页",
        "sLast": "尾页"
        }
    }
});');

