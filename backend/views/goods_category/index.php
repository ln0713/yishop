<table class="cate table table-hover table-bordered" >
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>上一层级</th>
        <th>分类简介</th>
<!--        <th>文章状态</th>-->
<!--        <th>创建时间</th>-->
        <th>分类操作</th>
    </tr>
    <?php foreach ($categories as $categorie):?>
        <tr data-lft="<?=$categorie->lft?>" data-rgt="<?=$categorie->rgt?>" data-tree="<?=$categorie->tree?>">
            <td><?=$categorie->id?></td>
            <td>
                <?php echo str_repeat(' - - ',$categorie->depth)?><?=$categorie->name?>
                <span class="toggle_cate glyphicon glyphicon-chevron-down" style="float: right"></span>
            </td>
            <td><?=$categorie->parent_id==0? '顶级分类' : $categorie->parent->name ?></td>
            <td><?=$categorie->intro?></td>
            <td><?php echo \yii\bootstrap\Html::a('删除',['goods_category/del?id='."$categorie->id"],['class'=>'btn btn-warning btn-xs']) ?>
                <?php echo \yii\bootstrap\Html::a('修改',['goods_category/edit?id='."$categorie->id"],['class'=>'btn btn-warning btn-xs']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\bootstrap\Html::a('添加分类',['goods_category/add'],['class'=>'btn btn-danger']);
?>
<?php
$js=<<<JS
    $(".toggle_cate").click(function(){
        //查找当前分类的子孙分类（根据房钱的tee lft rgt）
        var tr=$(this).closest('tr');
        var tree=parseInt(tr.attr('data-tree'));
        var lft=parseInt(tr.attr('data-lft'));
        var rgt=parseInt(tr.attr('data-rgt'));
        console.debug(rgt);
        //显示还是隐藏
        var show = $(this).hasClass('glyphcon-chevron-up')
        //切换图片
        $(this).toggleClass('glyphcon-chevron-up');
        $(this).toggleClass('glyphcon-chevron-down');
        $(".cate tr").each(function (){
            if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this)
            .attr('data-rgt'))<rgt){
                show?$(this).fadeIn():$(this).fadeOut();
            }
        });
    });
JS;
$this->registerJs($js);
//<?php
//分页工具条
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$page,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页',
//
//]);
