<?php
namespace frontend\widgets;

use frontend\models\Goods_category;
use yii\base\Widget;

/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/22
 * Time: 9:53
 */
class Goods_categoryWidget extends Widget
{
    public function init()
    {
        parent::init();
    }
//'class' => 'yii\redis\Connection',
//'hostname' => 'localhost',
//'port' => 6379,
//'database' => 0,
    public function run(){
        $redis= new \Redis();
        $redis->connect('127.0.0.1');
        $category_html=$redis->get('category_html');
        if($category_html==null){
            $categories = Goods_category::findAll(['parent_id'=>0]);
            $category_html = $this->renderFile('@app/widgets/view/category.php',['categories'=>$categories]);
            $redis->set('category_html',$category_html);
        }
        $redis->expire('category_html',3600*12);
        return $category_html;
    }
}