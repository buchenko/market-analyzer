<?php

use common\models\User;
use dektrium\user\controllers\SecurityController;
use yii\web\ForbiddenHttpException;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => [
        'log',
    ],
    'modules' => [
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'user' => [
            'class' => 'dektrium\user\Module',
            'admins' => ['admin'],
            'modelMap' => [
                'User' => [
                    'class' => User::class,
                    'on ' . User::AFTER_REGISTER => function (\yii\base\Event $event) {
                        $auth = Yii::$app->authManager;
                        $auth->assign($auth->getRole('trial'), $event->sender->id);
                    },
                ],
            ],
            'controllerMap' => [
                'security' => [
                    'class' => SecurityController::class,
                    'on ' . SecurityController::EVENT_AFTER_LOGIN => function (\yii\base\Event $event) {
                        if (!Yii::$app->user->can('adminPanel')) {
                            Yii::$app->user->logout();
                            throw new ForbiddenHttpException();
                        }
                    },
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'session' => [
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'defaultRoute' => '/user/admin',
    'params' => $params,
];
