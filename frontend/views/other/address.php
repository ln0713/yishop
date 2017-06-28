
<?php
use yii\helpers\Html;
\frontend\assets\ListAsset::register($this);
?>
<div style="clear:both;"></div>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach ($ads as $key=> $ad):?>
                <dl>
                    <dt>
                        <?php echo $key+1 ?>.
<!--                        排列序号-->
                        <?php echo $ad->name ?>&nbsp;
<!--                        收货人-->
                        <?php echo $ad->provinces->name ?>
<!--                        省-->
                        <?php echo $ad->citys->name ?>
<!--                        市-->
                        <?php echo $ad->districts->name ?>
<!--                        县区-->
                        <?php echo $ad->address ?>&nbsp;
<!--                        详细地址-->
                        <?php echo $ad->tel ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--                        电话号码-->
                        <span style="color: #00a0e9"><?php echo $ad->status==1? '默认地址' : ' ' ?></span>
                    </dt>
                    <dd>
                        <?php echo \yii\helpers\Html::a('删除',['other/del_address','id'=>$ad->id],['class'=>'btn btn-warning btn-xs'])?>
                        <?php echo \yii\helpers\Html::a('修改',['other/edit_address','id'=>$ad->id],['class'=>'btn btn-warning btn-xs'])?>
                        <?php echo \yii\helpers\Html::a('设为默认地址',['other/status_address','id'=>$ad->id],['class'=>'btn btn-warning btn-xs'])?>
                    </dd>
                </dl>
            <?php endforeach;?>
        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <ul>
                    <?php
                    $url=\yii\helpers\Url::toRoute(['get-region']);
                    $form=\yii\widgets\ActiveForm::begin([
                            'fieldConfig'=>[
                            'options'=>['tag'=>'li',],
                             'errorOptions'=>['tag'=>'a']],
                        ]
                    );
                    echo $form->field($model,'name')->textInput(['class'=>'txt']);//收货人
                    echo $form->field($model, 'area')->widget(\chenkby\region\Region::className(),[
                        'model'=>$model,
                        'url'=>$url,
                        'province'=>[
                            'attribute'=>'province',
                            'items'=>\frontend\models\Locations::getRegion(),
                            'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
                        ],
                        'city'=>[
                            'attribute'=>'city',
                            'items'=>\frontend\models\Locations::getRegion($model['province']),
                            'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
                        ],
                        'district'=>[
                            'attribute'=>'district',
                            'items'=>\frontend\models\Locations::getRegion($model['city']),
                            'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
                        ]
                    ]);
                    echo $form->field($model,'address')->textInput(['class'=>'txt address']);//详细地址
                    echo $form->field($model,'tel')->textInput(['class'=>'txt']);//电话号码
                    echo $form->field($model,'status')->checkbox();//电话号码
                    echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" class="btn" value="保存" />
                    </li>';
                    \yii\widgets\ActiveForm::end();
                    ?>
                </ul>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>