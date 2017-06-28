<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/22
 * Time: 19:08
 */

namespace backend\components;


use yii\base\Component;

class Time extends Component
{
    //开启计时器
    //用法:Echo Runtime(1);
    Function Runtime($mode=0){
        Static $s;
        IF(!$mode){
            $s=microtime();
            Return;
        }
        $e=microtime();
        $s=Explode(" ", $s);
        $e=Explode(" ", $e);
        Return Sprintf("%.2f ms",($e[1]+$e[0]-$s[1]-$s[0])*1000);
    }
}