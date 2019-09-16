<?php

namespace common\services;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * Class LeagueOfCommerce
 *
 * @property array $listCities
 * @property string $cities
 */
class LeagueOfCommerce extends Component
{
    public $apiUrl;

    public function init()
    {
        parent::init();
        $this->apiUrl = $this->apiUrl ?? ArrayHelper::getValue(Yii::$app->params, 'apiUrlLeagueOfCommerce', 'https://yiitest.development.clever-hosting.com/demos/cities.php');
    }

    /**
     * @param string $url
     *
     * @return array
     * @throws \yii\httpclient\Exception
     */
    protected function getDataFromApi(string $url = ''): array
    {
        $client = new Client([
            'baseUrl' => $this->apiUrl,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON,
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON,
            ],
        ]);
        $response = $client->get($url, ['name' => 'Yii 2.0'])->send();

        return Json::decode($response->getContent());
    }

    /**
     * @return mixed
     * @throws \yii\httpclient\Exception
     */
    public function getCities()
    {
        $cities = $this->getDataFromApi();

        return $cities;
    }

    /**
     * @return array
     * @throws \yii\httpclient\Exception
     */
    public function getListCities(): array
    {
        $listCities = ArrayHelper::map($this->getCities(), 'id', 'city');

        return $listCities;
    }

    /**
     * @param int $cityId
     *
     * @return array
     * @throws \yii\httpclient\Exception
     */
    public function getCity(int $cityId): array
    {
        $city = $this->getDataFromApi("?id=$cityId");

        return reset($city) ? reset($city) : [];
    }

    /**
     * @param int $cityId
     *
     * @return array
     * @throws \yii\httpclient\Exception
     */
    public function getGoodsOfCity(int $cityId): array
    {
        $goods = ArrayHelper::getValue($this->getCity($cityId), 'goods', []);

        return $goods;
    }

    /**
     * @param array $productFrom
     * @param array $productTo
     *
     * @return array
     */
    public function compareGoods(array $productFrom, array $productTo): array
    {
        $compared = [
            'name' => ArrayHelper::getValue($productFrom, 'name'),
            'amount' => ArrayHelper::getValue($productFrom, 'amount'),
            'image' => ArrayHelper::getValue($productFrom, 'image'),
            'difference' => ArrayHelper::getValue($productTo, 'price') - ArrayHelper::getValue($productFrom, 'price'),
        ];

        return $compared;
    }

    /**
     * @param int $fromCity
     * @param int $toCity
     *
     * @return array
     * @throws \yii\httpclient\Exception
     */
    public function compareGoodsOfCities(int $fromCity, int $toCity): array
    {
        $comparedGoods = [];
        $goodsFrom = $this->getGoodsOfCity($fromCity);
        $goodsFrom = ArrayHelper::index($goodsFrom, 'name');
        $goodsTo = $this->getGoodsOfCity($toCity);
        $goodsTo = ArrayHelper::index($goodsTo, 'name');
        $goodsNames = array_keys(array_intersect_key($goodsFrom, $goodsTo));
        foreach ($goodsNames as $name) {
            $productFrom = ArrayHelper::getValue($goodsFrom, $name);
            $productTo = ArrayHelper::getValue($goodsTo, $name);
            $compared = $this->compareGoods($productFrom, $productTo);
            if (ArrayHelper::getValue($compared, 'difference') > 0) {
                $comparedGoods[] = $compared;
            }
        }
        ArrayHelper::multisort($comparedGoods, 'difference', SORT_DESC);
        $comparedGoods = array_slice($comparedGoods, 0, 3);

        return $comparedGoods;
    }

}