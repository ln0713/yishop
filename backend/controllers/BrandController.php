<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 16:09
 */
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller{
    //品牌列表
    public function actionIndex(){
        //获取所有的品牌数据
        $query=Brand::find();
        //总条数  每页显示几条  当前第几页
        $total=$query->count();
        $page= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $brand=$query->offset($page->offset)->limit($page->limit)->all();
        //加载视图 并传送数据
        return $this->render('index',['brand'=>$brand,'page'=>$page]);
    }
    //品牌的添加
    public function actionAdd(){
        //实例化对象 request
        $request= new Request();
        //实例化对象 获取其中验证规则
        $model= new Brand();
        //判断请求方式
        if($request->isPost){//请求方式是否为post方式
            //加载post数据
            $model->load($request->post());
            //获取图片信息
            $model->img=UploadedFile::getInstance($model,'img');
            //验证数据
            if($model->validate()){
                //保存图片
                $fileName = '/images/'.uniqid().'.'.$model->img->extension;
                $model->img->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $model->logo = $fileName;
                //保存到数据表
                $model->save();
                \Yii::$app->session->setFlash('success','品牌添加成功');
                //跳转到列表页
                return $this->redirect(['brand/index']);
            }else{
                //打印验证失败错误信息
                var_dump($model->getErrors());exit;
            }
        }else{//请求方式不为post方式时
            // 加载添加页面  并
            return $this->render('add',['model'=>$model]);
        }
    }
    //品牌的删除
    public function actionDel($id){
        //获取id对应的品牌的信息
        $brand=Brand::findOne($id);
        //将其状态改为删除（-1）
        $brand->status=-1;
        //保存状态
        $brand->save(false);
//        var_dump($brand);exit;
        //删除后返回列表页
        return $this->redirect(['brand/index']);

    }
    //品牌的修改
    public function actionEdit($id){
        //获取对应id的用户信息
        $model = Brand::findOne(['id'=>$id]);
        //实例化对象
        $request = new Request();
        //判断请求方式
        if($request->isPost){//请求方式为post
            //加载数据
            $model->load($request->post());
            //删除原图片
            if($model->logo){unlink(\Yii::getAlias('@webroot').$model->logo);};
            //实例化文件对象
            $model->img=UploadedFile::getInstance($model,'img');
            //验证数据
            if($model->validate()){
                //保存图片
                $fileName = '/images/'.uniqid().'.'.$model->img->extension;
                $model->img->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $model->logo = $fileName;
                //保存到数据表
                $model->save();
                \Yii::$app->session->setFlash('success','品牌修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}
