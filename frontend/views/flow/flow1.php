<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//加载静态资源管理器，注册静态资源到当前布局文件

?>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as$k=> $model):?>
        <tr data-goods_id="<?=$model['id']?>">
            <td class="col1"><a href=""><?=Html::img('http://admin.yi2shop.com'.$model['logo'])?></a>  <strong><a href=""><?php echo $model['name'] ?></a></strong></td>
            <td class="col3">￥<span><?php echo $model['shop_price'] ?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?php echo $model['amount'] ?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span id="all_moeny<?php echo $k?>"><?=($model['shop_price']*$model['amount'])?>.00</span></td>
            <td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total">
                        <?php
                        $sum=0;
                         foreach ($models as $model){
                             $sum += $model['shop_price']*$model['amount'];
                         }
                        echo  $sum;
                        ?>.00
                    </span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <?php echo \yii\helpers\Html::a('继续购物',['index/index'],['class'=>"continue"])?>
        <?php echo \yii\helpers\Html::a('结 算',['flow/flow2'],['class'=>"checkout"])?>
    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<?php
$url = \yii\helpers\Url::to(['flow/add']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
           //监听+ - 按钮的点击事件
        $(".reduce_num,.add_num").click(function(){
            //console.log($(this));
            var goods_id = $(this).closest('tr').attr('data-goods_id');
            var amount = $(this).parent().find('.amount').val();
            //识别是在购物车中对数据处理
            var update = 1;
            //发送ajax post请求到flow/add  {goods_id,amount}
            $.post("$url",{goods_id:goods_id,amount:amount,update:update,"_csrf-frontend":"$token"});
        });
        //删除按钮
        $(".del_goods").click(function(){
            if(confirm('是否删除该商品')){
                var goods_id = $(this).closest('tr').attr('data-goods_id');
                //发送ajax post请求到flow/add {goods_id,amount}
                $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
                //删除当前商品的标签
                $(this).closest('tr').remove();
            }
        });
JS

));
