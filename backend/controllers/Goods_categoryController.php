<?php
namespace backend\controllers;

use backend\models\Goods_category;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\NotFoundHttpException;

class Goods_categoryController extends PassController{
    //首页
    public function actionIndex()
    {
        $categories = Goods_category::find()->orderBy('tree,lft')->all();
        return $this->render('index', ['categories' => $categories]);
    }
    public function actionAdd(){
        //实例化对象
        $request=new Request();
        $model=new Goods_category();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                $parent = Goods_category::findOne(['id'=>$model->parent_id]);//获取上一级分类
                $model->prependTo($parent);//添加到上一级分类下面
            }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods_category/index']);
        }
        //获取所有的分类
//        $categorys=Goods_category::find()->all();
        $categories = ArrayHelper::merge([['id'=>'0','name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->asArray()->all());


        return $this->render('add',['model'=>$model,'categories'=>$categories]);
        //判断请求方式,'categories'=>$categories
    }
    public function actionEdit($id)
    {
        $model = Goods_category::findOne(['id'=>$id]);
        $parent_id =$model->parent_id;
        //var_dump($parent_id);exit;
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //var_dump($model);exit;
                //添加非一级分类
                $parent = Goods_category::findOne(['id'=>$model->parent_id]);//获取上一级分类
                $model->prependTo($parent);//添加到上一级分类下面
            }else{
                //判断父id是否为o且有没有被改变
                if($model->parent_id==$parent_id && $model->parent_id==0){//true时修改分类
                    $model->save();
                }else{// false时添加一级分类
                    $model->makeRoot();
                }
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods_category/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //删除
    public function actionDel($id){
        //根据该id查询该类下面是否有子分类
        $models=Goods_category::findAll(['parent_id'=>$id]);
        if($models){//有分类时
            \Yii::$app->session->setFlash('success','该分类存在子分类 不能删除');
            return $this->redirect(['goods_category/index']);
            //throw new NotFoundHttpException('该分类存在子分类');
        }else{
            $model=Goods_category::findOne($id);
            if($model->parent_id==0){
                echo 1;
            }else{
                $model->delete();
            }
            //var_dump($id);
        }
    }
    public function actionTest(){
//        $goods_category = new Goods_category();
//        $goods_category ->name= '家用电器';
//        $goods_category ->parent_id = 0;
//        $goods_category ->intro = '11';
//        $goods_category->makeRoot();
//        var_dump($goods_category);
    }
    public function actionZtree(){
        $categories = Goods_category::find()->asArray()->all();

        return $this->renderPartial('ztree',['categories'=>$categories]);//不加载布局文件
    }
}