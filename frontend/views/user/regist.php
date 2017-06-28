<?php
use yii\helpers\Html;
?>
<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">

            <?php
            //注册表单  不需要使用bootstrap样式，所以使用\yii\widgets\ActiveForm
            $form = \yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li',
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]]
            );
            echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);//用户名
            echo ' <p>3-20位字符，可由中文、字母、数字和下划线组成</p> ';
            echo $form->field($model,'password'/*,[
                'options'=>[
                    'tag'=>'li',
                ]
            ]*/)->passwordInput(['class'=>'txt']);//密码
            echo '<p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>';
            echo $form->field($model,'affirm_password')->passwordInput(['class'=>'txt']);//确认密码
            echo '<p>两次输入密码必须一致</p>';
            echo $form->field($model,'email')->textInput(['class'=>'txt']);//邮箱
            echo '<p>邮箱必须合法 如 123456@qq.com</p>';
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);//手机号码
            $button =  Html::button('获取验证码',['id'=>'send_sms_button']);
            echo $form->field($model,'smsCode',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$button\n{hint}\n{error}"])->textInput(['class'=>'txt']);
            //验证码
            echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'user/captcha','template'=>'{input}{image}']);
            //用户协议
            echo '<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
						</li>';
            //提交按钮
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn">
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>
        </div>
        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>
    </div>
</div>
<!-- 登录主体部分end -->
<?php
/**
 * @var $this \yii\web\View
 */

$url =\yii\helpers\Url::to(['user/smsend']);
$this->registerJs( new \yii\web\JsExpression(
        <<<JS
        //点击发送短信验证码时
        $('#send_sms_button').click(function() {
          //获取手机号
          var tel=$('#member-tel').val();
          // console.log(tel); 测试接收手机号
          // AJAX.post提交tel到 user/smsend
          $.post('$url',{tel:tel},function(data) {
            if(data=='success'){
                //console.log('短信发送成功');
                alert('短信发送成功');
            }else{
                console.log(data);
                //alert(data);
            }
         });
        $('#member-smscode').prop('disabled',false);
			var time=10;
			var interval = setInterval(function(){
				time--;
				if(time<=0){
					clearInterval(interval);
					var html = '获取验证码';
					$('#send_sms_button').prop('disabled',false);
				} else{
					var html = time + ' 秒后再次获取';
					$('#send_sms_button').prop('disabled',true);
				}
				
				$('#send_sms_button').text(html);
			},1000);
	
        });
  
JS
))
?>
