<?php

namespace common\rbac;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Rule;

/**
 * Class UseLeagueOfCommerce
 * @package common\rbac
 */
class UseLeagueOfCommerce extends Rule
{
    public $name = 'useLeagueOfCommerce';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     *
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $request = Yii::$app->user->identity->request;
        $result = Yii::$app->user->can('admin')
            || Yii::$app->user->can('client')
            || (Yii::$app->user->can('trial') && $request < ArrayHelper::getValue(Yii::$app->params, 'maxTrialUseLeagueOfCommerce', 10));

        return $result;
    }

}