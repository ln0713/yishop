<div>
    <?php echo \yii\bootstrap\Html::a('商品分类列表',['category/index']); ?>
    <span>>>></span><span>商品分类添加</span>
</div>
<?php
/**
 * @var $this \yii\web\view
 */
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name')->textInput();//名称
echo $from->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
//echo $from->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($categorys,'id','name'),['prompt'=>'请选择上级分类id']);//上级分类$categorys
echo $from->field($model,'intro')->textarea();//简介
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
//    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($categories);
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
		    onClick: function(event, treeId, treeNode) {
                console.log(treeNode.id);
                //将选中节点的id赋值给表单parent_id
                $("#goods_category-parent_id").val(treeNode.id);
            }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//展开所有节点
    //获取当前节点的父节点（根据id查找）
    var node = zTreeObj.getNodeByParam("id", $("#goods_category-parent_id").val(), null);
    zTreeObj.selectNode(node);//选中当前节点的父节点
JS

);
$this->registerJs($js);
?>