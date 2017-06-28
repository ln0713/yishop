<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/12
 * Time: 8:29
 */

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Detail;
use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\Goods_day_count;
use backend\models\Goods_intro;
use backend\models\GoodsGallery;
use backend\models\Img;
use backend\models\Search;
use yii\web\Controller;
use yii\web\Request;
use yii\data\Pagination;
use xj\uploadify\UploadAction;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use backend\filters\AccessFilter;

class GoodsController extends PassController
{
    public function actionIndex(){
        $searchModel = new Goods();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionAdd(){
        //实例化对象
        $model=new Goods();
        $intros=new Goods_intro();
        $request=new Request();
        //获取所有的商品分类
        $categories = Goods_category::find()->asArray()->all();
        //获取所有的品牌
        $brand=Brand::find()->all();
        //var_dump($categories);exit;
        //判断请求方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $intros->load($request->post());
            //获得时间
            $model->create_time=time();
            //商品添加成功后 记录商品添加
            //根据当天时间判断是否有过添加记录
            //获取当天的时间
            $day=date('Ymd');
            //根据时间查询
            $count=Goods_day_count::findOne(['day'=>$day]);
            if($count){//查询结果true时
                //记录加一
                $count->count+=1;
            }else{//查询结果false时
                //是实例化对象
                $count=new Goods_day_count();
                //添加纪录
                $count->day=$day;
                $count->count=1;
            }
            //保存数据（记录商品添加）
            $count->save();
            //保存数据（商品信息）
            $model->sn=$day.str_pad($count->count,5,0,STR_PAD_LEFT);
            $model->save();
            $intros->goods_id=$model->id;
            $intros->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods/index']);
        }else{//请求方式不是post
            return $this->render('add',['model'=>$model,'categories'=>$categories,'brand'=>$brand,'intros'=>$intros]);
        }
    }
    public function actionDel($id){
        //获取id对应的商品的信息
        $goods=Goods::findOne($id);
        //将其状态改为删除（-1）
        $goods->status=0;
        //保存状态
        $goods->save(false);
//        var_dump($brand);exit;
        //删除后返回列表页
        return $this->redirect(['goods/index']);
    }
    public function actionEdit($id){
        //获取对应id的用户信息
        $model = Goods::findOne(['id'=>$id]);
        $intros = Goods_intro::findOne(['goods_id'=>$id]);
        //获取所有的品牌
        $brand=Brand::find()->all();
        //实例化对象
        $categories = Goods_category::find()->asArray()->all();
        $request = new Request();
        //判断请求方式
        if($request->isPost){//请求方式为post
            //加载数据
            $model->load($request->post());
            $intros->load($request->post());
//            //删除原图片
//            if($model->logo){unlink(\Yii::getAlias('@webroot').$model->logo);};
            //验证数据
            if($model->validate()){
                //保存到数据表
                $model->save();
                $intros->save();
                \Yii::$app->session->setFlash('success','商品修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('add',['model'=>$model,'categories'=>$categories,'brand'=>$brand,'intros'=>$intros]);
    }
    public function actionImgs($id){
        //实例化对象
        $model = new Img();
        //通过id查询所对应的所有的图片
        $imgs=Img::findall(['goods_id'=>$id]);
        if (\Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstances($model, 'file');

            if ($model->file && $model->validate()) {
                foreach ($model->file as $file) {
                    $file->saveAs('upload/789654/' . $file->baseName . '.' . $file->extension);
                    var_dump($file);
                    $img=new Img();
                    $img->goods_id=$id;
                    $img->img='upload/789654/' . $file->baseName . '.' . $file->extension;
                    $img->save();
                }
               return $this->redirect(['goods/index']);
            }
        }

        return $this->render('imgs', ['model' => $model,'imgs'=>$imgs]);
    }
    //商品详情
    public function actionNews($id){
        //商品详情
        $detail=Goods_intro::findOne(['goods_id'=>$id]);
        //商品名称
        $goods= Goods::findOne(['id'=>$id]);
        return $this->render('news',['detail'=>$detail,'goods'=>$goods]);
    }
    /*
         * 商品相册
         */
    public function actionImg($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('img',['goods'=>$goods]);
    }
    /*
     * AJAX删除图片
     */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');//GoodsGallery
        $model =GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //图片上传成功的同时，将图片和商品关联起来
                    $model = new GoodsGallery();
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $model->img = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->img;
                },
            ],
        ];
    }
}