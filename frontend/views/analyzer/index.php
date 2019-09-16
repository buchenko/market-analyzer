<?php

use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $cities array */
/* @var $goodsDataProvider \yii\data\ArrayDataProvider */

$this->title = 'Анализатор';
?>

<div class="container mrgn-t30">
    <div class="form">
        <?php $form = ActiveForm::begin([
            'method' => 'GET',
            'enableClientValidation' => false,
        ]); ?>
        <div class="row">
            <div class="col-md-4">
                <?=$form->field($model, 'fromCity')->widget(Select2::class, [
                    'data' => $cities,
                    'options' => [
                        'placeholder' => Yii::t('app', 'Выберите город'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);?>
            </div>
            <div class="col-md-4">
                <?=$form->field($model, 'toCity')->widget(Select2::class, [
                    'data' => $cities,
                    'options' => ['placeholder' => Yii::t('app', 'Выберите город')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);?>
            </div>
            <div class="col-md-4">
                <br>
                <?=Html::submitButton(Icon::show('stats', ['framework' => Icon::BSG]) . ' ' . Yii::t('app', 'Сравнить'),
                    ['class' => 'btn btn-primary'])?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <?php echo $this->render('_goods', ['goodsDataProvider' => $goodsDataProvider]); ?>
</div>
