<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'name' => "Krisia Holidays",
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
           // 'enablePrettyUrl' => true,
        ],
        'urlManagerBackend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/index.php',
           // 'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'hostInfo' => 'http://krisia-lms.dev/admin/',
           // 'scriptUrl' => 'http://krisia-lms.dev/admin'
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@mail',
            'htmlLayout' => '@mail/layouts/html',
            'textLayout' => '@mail/layouts/text',
        ],
    ],
    'params' => $params,
];
