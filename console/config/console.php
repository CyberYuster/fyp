<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'bootstrap' => ['log'],   
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
             'useFileTransport' => false,
            // and configure a transport for the mailer to send real emails.
            'transport' => [
                'scheme' => 'smtps',
                'host' => 'smtp.gmail.com',
                'username' => 'abilitapp16@gmail.com',
                'password' => 'mvcnnrbpyqihjtds',
                'port' => 465,
//                'dsn' => 'native://default',
                'encryption' => 'tls', // Change this to 'ssl' if necessary
            ],
        ],
        'db' => require(__DIR__ . '/../../common/config/db.php'),
    ],
    'params' => $params,
];
