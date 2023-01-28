<?php

use common\models\Receipt;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Receipts';
?>
<div class="receipt-index mt-5">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'purchaseDate',
            'total',
            [
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status == "Complete") 
                        return '<h5><span class="badge badge-sucess">Complete</span></h5>';
                    if ($model->status == "Pending") 
                        return '<h5><span class="badge badge-info">Pending</span></h5>';
                    if ($model->status == "Refund") 
                        return '<h5><span class="badge badge-danger">Refund</span></h5>';
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{pay} {view} {delete}',
                'buttons' => [
                    'pay' => function ($url, $model) {
                        if ($model->status != "Complete") {
                            return Html::a('<i class="fas fa-dollar-sign"></i>', $url, [
                                'title' => Yii::t('app', 'Pay'),
                                'class' => 'btn btn-sm btn-primary',
                            ]);
                        }
                    },
                    'view' => function ($url, $model) {
                        if ($model->status == "Complete") {
                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class' => 'btn btn-sm btn-primary',
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status != "Complete") {
                            return Html::a('<i class="fas fa-trash"></i>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to cancel this flight?'),
                                    'method' => 'post',
                                ],
                            ]);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'pay')
                        return Url::to(['pay', 'id' => $model->id]);
                    if ($action === 'view')
                        return Url::to(['view', 'id' => $model->id]);
                    if ($action === 'delete')
                        return Url::to(['delete', 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
