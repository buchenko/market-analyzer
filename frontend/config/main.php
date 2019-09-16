<?php

use common\models\User;
use dektrium\user\controllers\RegistrationController;
use dektrium\user\controllers\SecurityController;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'user' => [
            'class' => 'dektrium\user\Module',
            'as frontend' => 'dektrium\user\filters\FrontendFilter',
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
                'registration' => [
                    'class' => RegistrationController::class,
                    'on ' . RegistrationController::EVENT_AFTER_CONFIRM => function (\yii\base\Event $event) {
                        Yii::$app->getResponse()->redirect(Url::to(['/analyzer/index']),301)->send();
                        Yii::$app->end();
                    },
                ],
                'security' => [
                    'class' => SecurityController::class,
                    'on ' . SecurityController::EVENT_AFTER_LOGIN => function (\yii\base\Event $event) {
                        Yii::$app->getResponse()->redirect(Url::to(['/analyzer/index']),301)->send();
                        Yii::$app->end();
                    },
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
    'params' => $params,
];
