<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Flight $model */

$this->title = 'Client #' . $model->id . ' - Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">
    <div class="row m-4">
        <div class="col">
            <div class="row h1">Client info</div>
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => 'Full Name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->userData->fName . ' ' . $model->userData->surname;
                            }
                        ],
                        [
                            'label' => 'Username',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->username;
                            }
                        ],
                        [
                            'label' => 'Email',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->email;
                            }
                        ],
                        [
                            'label' => 'Phone',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->userData->phone;
                            }
                        ],
                        [
                            'label' => 'Gender',
                            'value' => function ($model) {
                                return $model->userData->gender;
                            }
                        ],
                        [
                            'label' => 'Application',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->client->application;
                            }
                        ],
                        [
                            'label' => 'Balance',
                            'value' => function ($model) {
                                return $model->client->balance . '€';
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="row m-4">
        <div class="col">
            <div class="row h1">Client Statistics</div>
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => Html::a('Tickets bought', ['ticket/index', 'client_id' => $model->id]),
                            'value' => function ($model) {
                                return count($model->client->tickets);
                            }
                        ],
                        [
                            'label' => Html::a('Receipts', ['receipt/index', 'client_id' => $model->id]),
                            'value' => function ($model) {
                                return count($model->client->tickets);
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
