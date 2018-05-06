<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['admin', 'quotation_manager', 'follow_up_manager', 'quotation_staff', 'follow_up_staff', 'booking_staff'],
        ],
    ],
];
