<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/12
 * Time: 19:33
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Img extends ActiveRecord
{
    public static function tableName(){
        return 'img';
    }
    public $file;
    public function rules()
    {
        return [
            [['file'], 'file', 'maxFiles' => 10], // <--- here!
        ];
    }
    public function  attributeLabels(){
        return [
          'file'=>'相册',
        ];
    }
}