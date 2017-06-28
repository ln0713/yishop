<?php
/**
 * Created by PhpStorm.
 * User: ln0713
 * Date: 2017/6/19
 * Time: 9:57
 */

namespace frontend\controllers;


use frontend\models\Goods_category;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;


class UserController extends Controller
{
    public $layout = 'login';
    //用户注册
    public function actionRegist(){
        $model = new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //注册时间
            $model->created_at=time();
            //密码加密
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            $model->save(false);
            \Yii::$app->session->setFlash('success','注册用户成功成功');
            return $this->redirect(['user/login']);
        }
        return $this->render('regist',['model'=>$model]);
    }

    //用户登录
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //成功登陆后 获取该用户的信息
            $user=Member::findOne(['username'=>$model->username]);
            //获取登陆主机的ip
            $last_ip=ip2long(\Yii::$app->request->userIP);
            //更新最后登录ip
            $user->last_login_ip=$last_ip;
            //更新最近登陆时间
            $user->last_login_time=time();
            $user->save(false);
            return $this->redirect(['index/index']);
        }
        return $this->render('login',['model'=>$model]);
    }
    //更新缓存 redis
    public function actionIndex()
    {
        //更新redis缓存
        $redis= new \Redis();
        $redis->connect('127.0.0.1');
        $categories = Goods_category::findAll(['parent_id'=>0]);
        $category_html = $this->renderFile('@app/widgets/view/category.php',['categories'=>$categories]);
        $redis->set('category_html',$category_html);
        echo '更新缓存成功';
    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }

    //退出 注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //测试
    public function actionSms(){
        $config = [
            'app_key'    => '24478557',
            'app_secret' => '631716ff6730f539a1f06d834c70910d',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];

    // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $code=rand(100000, 999999);
        $req->setRecNum('18308412969')
            ->setSmsParam([
                'code' => $code
            ])
            ->setSmsFreeSignName('李宁的网站')
            ->setSmsTemplateCode('SMS_71635101');

        $resp = $client->execute($req);
        var_dump($resp);
        var_dump($code);
    }
    public function actionSmsend(){
        //post接受电话号码 \=
        $tel=\Yii::$app->request->post('tel');
        //验证电话号码
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        //生成随机验证码
        $code=rand(100000, 999999);
        $result=1;
        //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        if($result){
            //保存当前验证码  redis 或 cache  不能保存到cookie
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            echo 'success'.$code;
        }else{
            echo '发送失败';
        }
    }

    public function actionMail()
    {
        //通过邮箱重设密码
        $result = \Yii::$app->mailer->compose()
            ->setFrom('ning1343311935@163.com')//谁的邮箱发出的邮件
            ->setTo('ning1343311935@163.com')//发给谁
            ->setSubject('六月感恩季，七牛献豪礼')//邮件的主题
            //->setTextBody('Plain text content')//邮件的内容text格式
            ->setHtmlBody('<b style="color: red">创办于2011年的七牛云，一直聚焦以数据为核心的云服务市场，为企业提供数据存储、CDN加速、富媒体处理、直播云等服务。成立六年来，在绝大多数人的印象中，七牛云这个名词是和技术与服务紧紧相连的。在云服务这个竞争激烈、变化巨大的领域中，七牛云已经成长为国内领先的企业级云服务商，其中为客户称道的无疑是前瞻的技术与用心的服务。<a href="http://www.xxx.com/user/resetpass?token=sdfhkjshdfjsdf1243">点此重设密码</a></b>')//邮件的内容 html格式
            ->send();
        if($result){

        }
    }
}