<?php

namespace frontend\controllers;

use frontend\models\AnalyzerForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Class AnalyzerController
 * @package frontend\controllers
 */
class AnalyzerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('useLeagueOfCommerce');
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    $message = Yii::t('app', "Вы исчерпали свой лимит показов, и если хотите получить неограниченный доступ к сервису, пожалуйста оплатите подписку");
                    throw new ForbiddenHttpException($message);
                },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\httpclient\Exception
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $goodsDataProvider = new ArrayDataProvider([
            'sort' => [
                'attributes' => ['name', 'amount', 'difference'],
            ],
        ]);
        $model = new AnalyzerForm();
        /**
         * @var $leagueOfCommerce \common\services\LeagueOfCommerce
         */
        $leagueOfCommerce = Yii::$app->leagueOfCommerce;
        if ($model->load($get) && $model->validate()) {
            $user = Yii::$app->user->identity;
            /**
             * @var $user \common\models\User
             */
            $user->request++;
            $user->update(false, ['request']);

            /**
             * @var $leagueOfCommerce \common\services\LeagueOfCommerce
             */
            $leagueOfCommerce = Yii::$app->leagueOfCommerce;
            $goodsDataProvider->allModels = $leagueOfCommerce->compareGoodsOfCities($model->fromCity, $model->toCity);
        }

        return $this->render('index', [
            'model' => $model,
            'cities' => $leagueOfCommerce->getListCities(),
            'goodsDataProvider' => $goodsDataProvider,
        ]);
    }

}
