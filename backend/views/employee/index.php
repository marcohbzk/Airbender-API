<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Employees';
?>
<div class="employee-index">

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
        <?= Html::a('+ Create Employee', ['create'], ['class' => 'btn btn-dark']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model) {
            if ($model->status != 10)
                return ['style' => "color:gray",];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Full Name',
                'value' => function ($model) {
                    return (isset($model->userData->fName) ? $model->userData->fName : 'Not set') . '   ' . (isset($model->userData->surname) ? $model->userData->surname : 'Not set');
                }
            ],
            [
                'label' => 'Email',
                'value' => function ($model) {
                    return isset($model->email) ? $model->email : "Not set";
                }
            ],
            [
                'label' => 'Phone',
                'value' => function ($model) {
                    return isset($model->userData->phone) ? $model->userData->phone : "Not set";
                }
            ],
            [
                'label' => 'Gender',
                'value' => function ($model) {
                    return isset($model->userData->gender) ? ($model->userData->gender == 'M' ? 'Male' : 'Female') : "Not set";
                }
            ],
            [
                'label' => 'Role',
                'value' => function ($model) {
                    return isset($model->authAssignment->item_name) ? $model->authAssignment->item_name : "Not set";
                }
            ],
            [
                'label' => 'Salary',
                'value' => function ($model) {
                    return isset($model->employee->salary) ? $model->employee->salary . '€' : "Not set";
                }
            ],
            [
                'label' => 'Airport',
                'value' => function ($model) {
                    return isset($model->employee->airport) ? $model->employee->airport->country . ' - ' . $model->employee->airport->city : "Not set";
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {activate}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model->status == 10) {
                            return Html::a('<i class="fas fa-eye"></i>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class' => 'btn btn-sm btn-primary',
                            ]);
                        }
                    },
                    'update' => function ($url, $model) {
                        if ($model->status == 10) {
                            return Html::a('<i class="fas fa-edit"></i>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class' => 'btn btn-sm btn-primary',
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status == 10) {
                            return Html::a('<i class="fas fa-trash"></i>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]);
                        }
                    },
                    'activate' => function ($url, $model) {
                        if ($model->status != 10) {
                            return Html::a('<i class="fas fa-check"></i>', $url, [
                                'title' => Yii::t('app', 'Activate'),
                                'class' => 'btn btn-sm btn-success',
                            ]);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['view', 'user_id' => $model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['update', 'user_id' => $model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['delete', 'user_id' => $model->id]);
                    }
                    if ($action === 'activate') {
                        return Url::to(['activate', 'user_id' => $model->id]);
                    }
                }
            ],
        ],
    ]); ?>



</div>