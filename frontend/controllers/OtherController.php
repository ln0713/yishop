<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/20
 * Time: 20:01
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Brand;
use frontend\models\Goods;
use frontend\models\Goods_category;
use frontend\models\Img;
use frontend\models\Locations;
use frontend\models\Order;
use frontend\models\Order_goods;
use yii\data\Pagination;
use yii\web\Controller;

class OtherController extends Controller
{
    public $layout = 'list';
    public function actionAddress(){
        $model = new Address();
        $ads=Address::find()->where(['nid'=>\Yii::$app->user->getId()])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->nid=\Yii::$app->user->getId();
            //判断新添加的地址是否为设为默认地址
            if($model->status==1){
                $this->getStatus();
            }
            $model->save();
            \Yii::$app->session->setFlash('success','添加新地址成功');
            return $this->redirect(['other/address']);
        }
        return $this->render('address',['model'=>$model,'ads'=>$ads]);
    }

    public function actionDel_address($id){
        //防sql注入
        $id=$id-0;
        $adds = Address::findOne($id);
        //防id注入 查看 修改 他人的收货地址
        if($adds->nid!=\Yii::$app->user->getId()){
            return $this->redirect(['other/address']);
        }
        //执行删除语句
         Address::findOne($id)->delete();
        //删除成功后 返回地址添加页面
        return $this->redirect(['other/address']);
    }

    public function actionEdit_address($id){
        $model = new Address();
        //防sql注入
        $id=$id-0;
        $adds = Address::findOne($id);
        //防id注入 查看 修改 他人的收货地址
        if($adds->nid!=\Yii::$app->user->getId()){
            return $this->redirect(['other/address']);
        }
        //查询该用户所有的地址
        $ads=Address::find()->where(['nid'=>\Yii::$app->user->getId()])->all();
        //判断请求方式
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断新添加的地址是否为设为默认地址
            if($model->status==1){
                $this->getStatus();
            }
            $model->save();
            \Yii::$app->session->setFlash('success','修改地址成功');
            return $this->redirect(['other/address']);
        }
        return $this->render('address',['model'=>$model,'ads'=>$ads]);
    }
    //直接设置默认值
    public function actionStatus_address($id){
        //防sql注入
        $id=$id-0;
        $adds = Address::findOne($id);
        //防id注入 查看 修改 他人的收货地址
        if($adds->nid!=\Yii::$app->user->getId()){
            return $this->redirect(['other/address']);
        }
        //调用状态方法
        $this->getStatus();
        //修改状态
        $adds->status=1;
        $adds->save();
        return $this->redirect(['other/address']);
    }

    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>\frontend\models\Locations::className()
        ];
        return $actions;
    }
    //查询状态为默认的 清除默认状态的默认
    public function getStatus(){
        $nid=\Yii::$app->user->getId();
        $model = Address::findOne(['nid'=>$nid,'status'=>1]);
        if($model){
            $model ->status='0';
            $model->save();
        }
        return true;
    }

    public function actionList($id){
        $brand=Brand::find()->all();
        //判断所传来的id 代表商品分类的成级
        $depth=Goods_category::findOne($id);
        $children=$depth->children;

        if($children){
            $child_id=[];
           foreach ($children as $child){
               $categoty=$child->children;
               if($categoty){
                   foreach ($categoty as $cate){
                       $child_id[] =$cate->id;
                   }

               }
               $child_id[]=$child->id;
           }
            $query=Goods::find()->where(['goods_category_id' => $child_id]);
        }else{
            $query=Goods::find()->where(['goods_category_id' => $id]);
        }
        //获取所有的该品牌分类的所有的商品
        //总条数  每页显示几条  当前第几页
        $total=$query->count();
        $page= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>6,
        ]);
        $goodses=$query->offset($page->offset)->limit($page->limit)->all();
        $tree=Goods_category::findOne(['id'=>$id]);
        $goods_category=Goods_category::findOne(['tree'=>$tree->tree,'parent_id'=>0]);
        return $this ->render('list',['brands'=>$brand,'goodses'=>$goodses,'goods_category'=>$goods_category,'id'=>$id]);
    }

    public function actionGoods($id){
        $goodses=Goods::findOne($id);
        return $this ->render('list',['goods'=>$goodses]);
    }

    public function actionGood($id){
        $goods=Goods::findOne($id);
        $imgs=Img::find()->where(['goods_id'=>$id])->all();
        return $this ->render('goods',['goods'=>$goods,'imgs'=>$imgs]);
    }

    public function actionOrder(){
        $nid=\Yii::$app->user->getId();
        $orders=Order::find()->where(['member_id'=>$nid])->all();
        return $this->render('order',['orders'=>$orders]);
    }

    public function actionOrder_goods($order_id){
        $order_goods=Order_goods::findAll(['order_id'=>$order_id]);
        return $this->render('order_goods',['order_goods'=>$order_goods]);
    }
}