<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/18
 * Time: 11:02
 */

namespace backend\controllers;


//class MenuController extends PassController
use backend\models\Menu;
use yii\web\Controller;
use backend\filters\AccessFilter;

class MenuController extends PassController
{

    //添加菜单
    public function actionAdd(){
        $model= new Menu();
        //获取所有的菜单
        $menus= Menu::find()->All();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if(!$model->parent_id){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','添加菜单成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model,'menus'=>$menus]);
    }
    //菜单列表
    public function actionIndex(){
        $menus= Menu::find()->All();
        return $this->render('index',['menus'=>$menus]);
    }
    //删除菜单
    public function actionDel($id){
        //获取对应的数据
        $menu=Menu::findOne(['id'=>$id]);
        if(!$menu){
            \Yii::$app->session->setFlash('danger','该菜单不存在');
        }
        $menus=Menu::findAll(['parent_id'=>$id]);
        if($menus){
            \Yii::$app->session->setFlash('danger','该菜单下面有子菜单 不能删除');
        }else{
            $menu->delete();
            \Yii::$app->session->setFlash('success','删除菜单成功');
        }
        return $this->redirect(['menu/index']);
    }
    //修改菜单
    public function actionEdit($id){
        //实例化对象
        $model=Menu::findOne(['id'=>$id]);
        //获取所有的菜单
        $menus= Menu::find()->All();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if(!$model->parent_id){
                $model->parent_id=0;
            }
            $model->save();
            \Yii::$app->session->setFlash('success','修改菜单成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model,'menus'=>$menus]);

    }
}