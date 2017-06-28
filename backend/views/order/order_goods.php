<table class="table">
    <tr>
        <th>ID</th>
        <th>订单id</th>
        <th>商品id</th>
        <th>商品名称</th>
        <th>商品logo</th>
        <th>商品价格</th>
        <th>商品数量</th>
        <th>小计</th>
    </tr>
    <?php foreach ($order_goodss as $order_good):?>
        <tr>
            <td><?=$order_good->id?></td>
            <td><?=$order_good->order_id?></td>
            <td><?=$order_good->goods_id?></td>
            <td><?=$order_good->goods_name?></td>
            <td><?php echo \yii\bootstrap\Html::img("$order_good->logo",['width'=>80]) ?></td>
            <td><?=$order_good->price?></td>
            <td><?=$order_good->amount?></td>
            <td><?=$order_good->total?></td>
        </tr>
    <?php endforeach;?>
</table>
<div></div>
<?php if (Yii::$app->user->can('order/index')) {
    echo \yii\bootstrap\Html::a('返回订单列表',['order/index'],['class'=>'btn btn-danger']);}
?>
<?php
//分页工具条
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$page,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页',
//
//]);
