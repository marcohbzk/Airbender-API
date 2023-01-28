<?php

use common\models\BalanceReq;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="balance-req-history">
    <div class="gtco-section">
        <div class="gtco-container">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-6">
                            <h1>History</h1>
                        </div>
                        <div class=" col">
                            <h2 class="text-right"><a href="index">View pending</a></h2>
                        </div>
                    </div>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'label' => 'Amount',
                                'value' => function ($model) {
                                    return $model->amount . '€';
                                }
                            ],
                            'status',
                            'requestDate',
                            'decisionDate',
                            [
                                'label' => 'Decision By',
                                'value' => 'balanceReqEmployee.employee.username'
                            ],

                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</div>