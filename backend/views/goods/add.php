<?php ;
use yii\web\JsExpression;
use xj\uploadify\Uploadify;

$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name')->textInput();//商品名称
echo $from->field($model,'logo')->hiddenInput();//LOGO图片
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址(data.fileUrl)写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传成功后的图片地址(data.fileUrl)写入logo字段
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->logo){
    echo \yii\helpers\Html::img($model->logo,['height'=>'50']);
}else{
    echo \yii\helpers\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
}

echo $from->field($intros,'content')->textarea();//商品详情
echo $from->field($model,'goods_category_id')->hiddenInput();//商品分类id
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $from->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brand,'id','name'),['prompt'=>'请选择品牌分类']);//文章分类id

echo $from->field($model,'market_price')->textarea();//市场价格
echo $from->field($model,'shop_price')->textarea();//商品价格
echo $from->field($model,'stock')->textarea();//库存
echo $from->field($model,'is_on_sale',['inline'=>'true'])->radioList([0=>'下架','1'=>'出售']);//是否在售
echo $from->field($model,'status',['inline'=>'true'])->radioList([0=>'回收站','1'=>'正常']);//状态
echo $from->field($model,'sort')->textarea();//排序

echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

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
                //将选中节点的id赋值给表单brand_id
                $("#goods-goods_category_id").val(treeNode.id);
                //console.log($("#goods-goods_category_id").val());
            }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    zTreeObj.expandAll(true);//展开所有节点
    //获取当前节点的父节点（根据id查找）
    var node = zTreeObj.getNodeByParam("id", $("#goods-goods_category_id").val(), null);
    zTreeObj.selectNode(node);//选中当前节点的父节点
JS

);
$this->registerJs($js);
?>