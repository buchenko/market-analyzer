<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $goodsDataProvider \yii\data\ArrayDataProvider */

if (empty($goodsDataProvider->models)): ?>
    <div class="row">
        <div class="col-md-12">
            <?=Yii::t('app', 'Нет товаров с положительной маржой')?>
        </div>
    </div>
<?php else: ?>
    <h3><?=Yii::t('app', 'Рекомендованные товары:')?></h3>
    <?=GridView::widget([
        'dataProvider' => $goodsDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($data) {
                    $url = ArrayHelper::getValue($data, 'image', '');

                    return Html::img($url, ['width' => 66, 'height' => 66]);
                },
                'label' => '',
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('app', 'Наименование товара'),
            ],
            [
                'attribute' => 'amount',
                'label' => Yii::t('app', 'Доступное количество'),
            ],
            [
                'attribute' => 'difference',
                'label' => Yii::t('app', 'Разница в цене'),
            ],
        ],
    ]);
    ?>
<?php endif; ?>


