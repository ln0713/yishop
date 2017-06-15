<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/14
 * Time: 11:18
 */

namespace backend\controllers;


use backend\models\UserEd;
use backend\models\User;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
//use backend\controllers\PassController;
use yii\web\Request;

class UserController extends PassController
{
    public function actionIndex(){
        $query=User::find();
        $total=$query->count();
        $page= new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>1,
        ]);
        $user=$query->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index',['users'=>$user,'page'=>$page]);
    }
    public function actionAdd(){
        //实例化对象
        $request=new Request();
        $model = new User();
        //判断请求方式是否为post
        if($request->isPost){
            $model->load($request->post());
            //验证数据
            if($model->validate()) {
                //保存到数据表
                //var_dump($model);exit;
                //注册时间
                $model->created_at=time();
                //密码加密
                //Yii::$app->security->generatePasswordHash('123456')
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);
                return $this->redirect(['user/index']);
            }
        }
            return $this->render('add',['model'=>$model]);


    }
    public function actionDel($id){

    }
    public function actionEdit($id){
        $model= UserEd::findOne(['id'=>$id]);
        $request=new Request();
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){
                    //保存到数据表
                    $model->updated_at=time();
                    if($model->old_password){
                        $model->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                    }
                    $model->save();
                    \Yii::$app->session->setFlash('success','用户信息修改修改成功');
                    return $this->redirect(['user/index']);
                }
            }
            return $this->render('edit',['model'=>$model]);
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
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}