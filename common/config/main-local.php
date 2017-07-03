<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=centos',
            'username' => 'ning',
            'password' => '770880ning',
            'charset' => 'utf8',
        ],
        'mailer' => [
          'class' => 'yii\swiftmailer\Mailer',
          'transport' => [
              'class' => 'Swift_SmtpTransport',
              'host' => 'smtp.163.com',
              'username' => 'ning1343311935@163.com',
              'password' => 'ning770880',
              'port' => '25', //163 ssl 465/994 非 25
              'encryption' => 'tls',
          ],
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
    ],
];
