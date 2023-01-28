<?php

use common\models\BalanceReq;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Balance Reqs';
?>
<a href="history">View history</a>
<div class="balance-req-index">
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'requestDate',
            [
                'label' => 'Amount',
                'value' => function ($model) {
                    return $model->amount . '€';
                }
            ],
            [
                'label' => 'Client username',
                'value' => function ($model) {
                    return $model->client->user->username;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{accept} {decline}',
                'buttons' => [
                    'accept' => function ($url) {
                        return Html::a(
                            '<span class="btn btn-success">Accept</span>',
                            $url,
                            [
                                'title' => 'Accept',
                                'data-pjax' => '0',
                            ],
                        );
                    },
                    'decline' => function ($url) {
                        return Html::a(
                            '<span class="btn btn-danger">Decline</span>',
                            $url,
                            [
                                'title' => 'Decline',
                                'data-pjax' => '0',
                            ],
                        );
                    }
                ],
            ],
        ],
    ]); ?>
</div>
