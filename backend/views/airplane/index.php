<?php

use common\models\Airplane;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Airplanes';
?>
<div class="airplane-index">

    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Sucess!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')) : ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-minus"></i>Error!</h4>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div class="d-flex m-2 justify-content-end">
        <?= Html::a('+ Create Airplane', ['create'], ['class' => 'btn btn-dark']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'luggageCapacity',
                'value' => function ($model) {
                    return $model->luggageCapacity . 'kg';
                }
            ],
            [
                'label' => 'Seats (Rows - Columns)',
                'value' => function ($model) {
                    return $model->minLinha . $model->minCol . ' - ' . $model->maxLinha . $model->maxCol;
                }
            ],
            [
                'label' => 'Economic',
                'value' => function ($model) {
                    return $model->economicStart . ' - ' . $model->economicStop;
                }
            ],
            [
                'label' => 'Normal',
                'value' => function ($model) {
                    return $model->normalStart . ' - ' . $model->normalStop;
                }
            ],
            [
                'label' => 'Luxury',
                'value' => function ($model) {
                    return $model->luxuryStart . ' - ' . $model->luxuryStop;
                }
            ],
            [
                'label' => 'Total Seats',
                'value' => function ($model) {
                    return $model->countTotalSeats();
                }
            ],
            'status',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status == "Active") {
                            return Html::a('<i class="fas fa-minus"></i>', $url, [
                                'title' => Yii::t('app', 'Deactivate'),
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]);
                        } else {
                            return Html::a('<i class="fas fa-check"></i>', $url, [
                                'title' => Yii::t('app', 'Activate'),
                                'class' => 'btn btn-sm btn-success',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['view', 'id' => $model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['update', 'id' => $model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['delete', 'id' => $model->id]);
                    }
                }
            ],
        ],
    ]); ?>

</div>
