<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'Анализатор рынков',
    'language' => 'ru-ru',
    'components' => [
        'leagueOfCommerce' => [
            'class' => 'common\services\LeagueOfCommerce',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => '\dektrium\rbac\components\DbManager',
            //'cache' => 'cache' //Включаем кеширование
        ],
    ],
];
