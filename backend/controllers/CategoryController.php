<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 18:45
 */
namespace backend\controllers;

use backend\models\Category;

use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class CategoryController extends Controller {
    public function actionIndex(){
        //分类列表
        //获取所有的分类数据
        $query=Category::find();
        //总条数  每页显示几条  当前第几页
        $total=$query->count();
        $page= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $category=$query->offset($page->offset)->limit($page->limit)->all();
        //加载视图 并传送数据
        return $this->render('index',['category'=>$category,'page'=>$page]);
    }
    public function actionAdd(){
        //实例化对象 request
        $request = new Request();
        //实例化表model 获取规则
        $model=new Category();
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //数据验证
            if($model->validate()){
                //保存数据
                $model->save();
                return $this->redirect(['category/index']);
            }else{
                //打印验证失败错误信息
                var_dump($model->getErrors());exit;
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }
    //分类的删除
    public function actionDel($id){
        //获取id对应的分类的信息
        $brand=Category::findOne($id);
        //将其状态改为删除（-1）
        $brand->status=-1;
        //保存状态
        $brand->save(false);
        //删除后返回列表页
        return $this->redirect(['category/index']);
    }
    //品牌的修改
    public function actionEdit($id)
    {
        //获取对应id的用户信息
        $model = Category::findOne(['id' => $id]);
        //实例化对象
        $request = new Request();
        //判断请求方式
        if ($request->isPost) {//请求方式为post
            //加载数据
            $model->load($request->post());
            //验证数据
            if ($model->validate()) {
                //保存到数据表
                $model->save();
                \Yii::$app->session->setFlash('success', '分类修改成功');
                return $this->redirect(['category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}