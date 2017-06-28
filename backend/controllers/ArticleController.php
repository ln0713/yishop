<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/8
 * Time: 16:09
 */
namespace backend\controllers;

use backend\models\Article;
use backend\models\Brand;
use backend\models\Category;
use backend\models\Detail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use backend\filters\AccessFilter;

class ArticleController extends PassController{
    //品牌列表
    public function actionIndex(){
        //获取所有的文章数据
        $query=Article::find();
        //总条数  每页显示几条  当前第几页
        $total=$query->count();
        $page= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $article=$query->offset($page->offset)->limit($page->limit)->all();
        //加载视图 并传送数据
        return $this->render('index',['article'=>$article,'page'=>$page]);
    }
    //品牌的添加
    public function actionAdd(){
        //实例化对象 request
        $request= new Request();
        //实例化对象 获取其中验证规则
        $model= new Article();
        //获取所有的品牌数据
        $category=Category::find()->all();
        $detail = new Detail();
        //判断请求方式
        if($request->isPost){//请求方式是否为post方式
            //加载post数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //设置添加时间
                $model->create_time=time();
                $model->save();
                $detail->article_id=$model->id;
                $detail->content=$detail->content;
                $detail->save();
                \Yii::$app->session->setFlash('success','品牌添加成功');
                //跳转到列表页
                return $this->redirect(['article/index']);
            }else{
                //打印验证失败错误信息
                var_dump($model->getErrors());exit;
            }
        }else{//请求方式不为post方式时
            // 加载添加页面  并
            return $this->render('add',['model'=>$model,'category'=>$category,'detail'=>$detail]);
        }
    }
    //品牌的删除
    public function actionDel($id){
        //获取id对应的品牌的信息
        $brand=Article::findOne($id);
        //将其状态改为删除（-1）
        $brand->status=-1;
        //保存状态
        $brand->save(false);
//        var_dump($brand);exit;
        //删除后返回列表页
        return $this->redirect(['article/index']);

    }
    //文章的修改
    public function actionEdit($id){
        //获取对应id的用户信息
        $model = Article::findOne(['id'=>$id]);
        $category=Category::find()->all();
        //文章详情
        $details=new Detail();
        $detail = $details->getDetail($id);
        //$detail = Detail::findOne(['article_id'=>$id]);
       //var_dump($detail);exit;
        //实例化对象
        $request = new Request();
        //判断请求方式
        if($request->isPost){//请求方式为post
            //加载数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证数据
            if($model->validate() && $detail->validate()){
                //保存到数据表
                $model->save();
                //文章详情加载数据
                $detail->save();
                \Yii::$app->session->setFlash('success','文章修改成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'category'=>$category,'detail'=>$detail]);
    }
    //查看文章详情
    public function actionDetail($id){
        //获取文章详情
        $detail = Detail::findOne(['article_id'=>$id]);
        //文章标题
        $article=Article::findOne(['id'=>$id]);
        return $this->render('detail',['detail'=>$detail,'article'=>$article]);

    }
    public function actions()
    {
        return [

            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
        ];
    }
}
