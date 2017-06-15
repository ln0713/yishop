<h1>商品列表</h1>
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'id' => 'cateadd-form',
    'options' => ['class' => 'form-horizontal'],
]); ?>
<div style="height: 50px">
    <?php echo \yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-danger']); ?>
    <?php echo \yii\bootstrap\Html::a('查看全部',['goods/index'],['class'=>'btn btn-danger']); ?>
</div>
<div>
    <div style="width: 200px;float: left">
        <?= $form->field($searchModel, 'sname',[
            'options'=>['class'=>''],
            'inputOptions' => ['placeholder' => '输入商品名','class' => 'input-sm form-control'],
        ])->label(false) ?>
    </div>
    <div style="width: 200px;float: left;margin-left: 20px">
        <?= $form->field($searchModel, 'sn',[
            'options'=>['class'=>''],
            'inputOptions' => ['placeholder' => '输入货号','class' => 'input-sm form-control'],
        ])->label(false) ?>

    </div>
    <span style="margin-left: 20px"><?= \yii\bootstrap\Html::submitButton('搜索', ['class' => 'btn btn-sm btn-primary']) ?></span>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
    use yii\grid\GridView;
    echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'=> '{items}<div class="text-right tooltip-demo">{pager}</div>',
    'pager'=>[
        //'options'=>['class'=>'hidden']//关闭自带分页
        'firstPageLabel'=>"首页",
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
        'lastPageLabel'=>'尾页',
    ],
    'columns'=>[
            ['label' =>'商品名' , 'value'=>'name'],
            ['label' =>'货号' , 'value'=>'sn'],
            ['label' =>'商品品牌' , 'value'=>'brand.name'],
            ['label' =>'商品分类' , 'value'=>'goods_category.name'],
            ['label' =>'商品LOGO' , 'format'=>'raw','value'=>function($good)       {
                return \yii\bootstrap\Html::img($good->logo,['width'=>60]);
            }],
            ['label' =>'市场价格' , 'value'=>'market_price'],
            ['label' =>'商品价格' , 'value'=>'shop_price'],
            ['label' =>'商品价格' , 'value'=>'stock'],
            ['label' =>'是否售价' , 'value'=>function($good)       {
                    return \backend\models\Goods::$saleOptions[$good->is_on_sale];
            }],
            ['label' =>'商品状态' , 'value'=>function($good)       {
                return \backend\models\Goods::$statuOptions[$good->status];
            }],
            ['label' =>'添加时间' , 'format'=>['date','php:Y-m-d H:i:s'],'value'=>'create_time'],
            [
                'class'=>'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{delete}  {update} {img} {news}',
                'buttons' => [
                    'delete'=> function($url,$model,$key){
                        return \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-trash"></i>删除',
                            ['goods/del','id'=>$key],
                            ['class'=> 'btn btn-default btn-xs',
                                'data' => ['confirm' => '你确定要删除！']
                            ] );
                       },
                    'update'=> function($url,$model,$key){
                        return \yii\bootstrap\Html::a('<i class="fa fa-file"></i>修改',
                            ['goods/edit','id'=>$key],
                            ['class'=> 'btn btn-default btn-xs',]);
                    },
                    'img'=> function($url,$model,$key){
                        return \yii\bootstrap\Html::a('<i class="fa fa-file"></i>查看相册',
                            ['goods/imgs','id'=>$key],
                            ['class'=> 'btn btn-default btn-xs',]);
                    },
                    'news'=> function($url,$model,$key){
                        return \yii\bootstrap\Html::a('<i class="fa fa-file"></i>查看详情',
                            ['goods/news','id'=>$key],
                            ['class'=> 'btn btn-default btn-xs',]);
                    },
                ],

            ],
        ],
    ]);

?>

