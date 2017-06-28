<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//加载静态资源管理器，注册静态资源到当前布局文件

?>
<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <p>
                    <?php foreach ($address as $addres):?>
                    <input type="radio" name="address_id"  value="<?php echo $addres->id?>" <?php echo $addres->status==1? 'checked=checked': ''?>/>
                    <?php echo $addres->name;?>&nbsp;&nbsp;
                    <?php echo $addres->provinces->name;?>&nbsp;
                    <?php echo $addres->citys->name;?>&nbsp;
                    <?php echo $addres->districts->name; ?>&nbsp;
                    <?php echo $addres->address;?>&nbsp;
                    <?php echo $addres->tel;?></p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($shippings as$k=> $shipping):?>
                        <tr <?php echo $k==0 ? 'class="cur"' : ''?>>
                            <td class="shipping">
                                <input type="radio" name="shipping"  <?php echo $k==0 ? 'checked="checked"' : ''?> value="<?php echo $shipping['id']?>"/><?php echo $shipping['name']?>
                            </td>
                            <td class="shipping_price">￥<span><?php echo $shipping['price']?></span></td>
                            <td><?php echo $shipping['info']?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach ($payments as$k=> $payment):?>
                        <tr <?php echo $k==1 ? 'class="cur"' : ''?>>
                            <td class="col1"><input type="radio"  name="payment" <?php echo $k==1 ? 'checked="checked"' : ''?>  value="<?php echo $payment['id'] ?>"/><?php echo $payment['name'] ?></td>
                            <td class="col2"><?php echo $payment['info'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($models as $model):?>
                    <tr>
                        <td class="col1"><a href=""><?=Html::img('http://admin.yi2shop.com'.$model['logo'])?></a>  <strong><a href=""><?php echo $model['name'] ?></a></strong></td>
                        <td class="col3">￥<?php echo $model['shop_price'] ?></td>
                        <td class="col4"> <?php echo $model['amount'] ?></td>
                        <td class="col5"><span>￥<?php echo $model['shop_price']*$model['amount'] ?>.00</span></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><span class="count"><?php echo $count?></span>件商品，总商品金额：</span>
                                <em ><span class="count_price"><?php
                                        $sum=0;
                                        foreach ($models as $model){
                                            $sum += $model['shop_price']*$model['amount'];
                                        }
                                        echo  $sum;
                                        ?></span>
                                    .00
                                </em>
                            </li>
                            <li class="tfoot_price">
                                <span>运费：</span>
                                <em>￥<?php echo count($models)*10?>.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<span class="tfoot_all"><?php
                                    $sum=0;
                                    foreach ($models as $model){
                                        $sum += $model['shop_price']*$model['amount'];
                                    }
                                    echo  $sum+$count*10;
                                    ?></span>.00
                                </em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a href="javascript:void(0)" class="submits_btn"><span>提交订单</span></a>
        <p>应付总额：<strong>￥<span><?php
                    $sum=0;
                    foreach ($models as $model){
                        $sum += $model['shop_price']*$model['amount'];
                    }
                    echo  $sum+$count*10;
                    ?></span>.00元</strong></p>

    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<?php
$url = \yii\helpers\Url::to(['flow/order']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        //改变发送方式
        $(".shipping input").change(function(){
            //获取运费
           var a=$(this).closest('tr').find('.shipping_price span').html();
           //获取商品件数
           var b=$('.count').html()
           //总运费
           var shipping_rice ='￥'+a*b+'.00';
           $('.tfoot_price em').html(shipping_rice);
           //修改总金额
           var count_price =$('.count_price').html();
           //console.log(count_price);
           //获取商品总金额
           var tfoot_all =count_price*1+a*b;
           // console.log(tfoot_all);
            $('.tfoot_all').html(tfoot_all);
            $('.fillin_ft p strong span').html(tfoot_all);
           
        });
        //提交订单
        $('.submits_btn').click(function() {
            var addres=$('.address_info input:checked').val();//获取地址id
            var pay_select=$('.pay_select input:checked').val();//获取支付方式id
            var shipping=$('.shipping input:checked').val();//获取发送方式id
            var tfoot_all=$('.tfoot_all').html();//获取支付方式id
            // console.log(addres);
            // console.log(shipping);
            // console.log(pay_select);
            // console.log(tfoot_all);
            $.post("$url",{addres:addres,shipping:shipping,pay_select:pay_select,tfoot_all:tfoot_all,"_csrf-frontend":"$token"},function(data) {
              if(data=='success'){
                  //跳转
                  window.location.href="http://www.yi2shop.com/flow/flow3.html";
              }else(data==false) {
                  alert('商品库存不足'); 
              }
            });
    
        });
JS

));
