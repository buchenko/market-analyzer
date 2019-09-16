<?php
namespace frontend\models;

use yii\base\Model;
use Yii;

/**
 * Class AnalyzerForm
 * @package common\models
 */
class AnalyzerForm extends Model
{
    /**
     * @var
     */
    public $fromCity;
    /**
     * @var
     */
    public $toCity;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fromCity' => Yii::t('app', 'Из города'),
            'toCity' => Yii::t('app', 'В город'),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['fromCity', 'toCity'], 'required'],
            [['fromCity', 'toCity'], 'integer'],
            [['fromCity', 'toCity'], 'in', 'range' => array_keys(Yii::$app->leagueOfCommerce->getListCities())],
            [
                'fromCity',
                'compare',
                'compareAttribute' => 'toCity',
                'operator' => '!=',
                //'whenClient' => "function (attribute, value) {
                //        return $('#contractsearch-datesignedto').val() != '';
                //    }",
            ],

        ];
    }
}