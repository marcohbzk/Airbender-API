<?php

use common\models\BalanceReq;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Balance Reqs - History';
?>
<a href="index">View pending</a>
<div class="balance-req-history">

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
            'decisionDate',
            'status',
        ],
    ]); ?>
</div>
