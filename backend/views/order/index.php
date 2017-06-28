<table class="table">
    <tr>
        <th>ID</th>
        <th>用户</th>
        <th>收货人</th>
        <th>收货地址</th>
        <th>联系方式</th>
        <th>支付方式</th>
        <th>送货方式</th>
        <th>消费金额</th>
        <th>状态</th>
        <th>第三方交易号</th>
        <th>生成时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($orders as $order):?>
        <tr>
            <td><?=$order->id?></td>
            <td><?=$order->member->username?></td>
            <td><?=$order->name?></td>
            <td><?=$order->provinces->name?><?=$order->citys->name?><?=$order->areas->name?><?=$order->address?></td>
            <td><?=$order->tel?></td>
            <td><?=$order->delivery_name?></td>
            <td><?=$order->payment_name?></td>
            <td><?=$order->total?></td>
            <td><?=$order->status?></td>
            <td><?=$order->trade_no?></td>
            <td><?=$order->create_time?></td>
            <td><?php if (Yii::$app->user->can('order/update') && $order->status==1) {
                    echo '已发货';} ?>
                <?php if (Yii::$app->user->can('order/update') &&  $order->status==0) {
                    echo \yii\bootstrap\Html::a('发货',['order/update','id'=>$order->id],['class'=>'btn btn-warning btn-xs']);} ?>
                <?php if (Yii::$app->user->can('order/order_goods') ) {
                    echo \yii\bootstrap\Html::a('查看订单详情',['order/order_goods','order_id'=>$order->id],['class'=>'btn btn-warning btn-xs']);} ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<div></div>

<?php
//分页工具条
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$page,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页',
//
//]);
