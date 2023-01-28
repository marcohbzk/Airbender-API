<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Flight $model */

$this->title = 'Employee #' . $model->id . ' - Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">
    <div class="row m-4">
        <div class="col">
            <div class="row h1">Employee info</div>
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
                            'label' => 'Role',
                            'value' => function ($model) {
                                return $model->authAssignment->item_name;
                            }
                        ],
                        [
                            'label' => 'Salary',
                            'value' => function ($model) {
                                return $model->employee->salary . '€';
                            }
                        ],
                        [
                            'label' => 'Airport',
                            'format' => 'raw',
                            'value' => Html::a($model->employee->airport->country . ' - ' . $model->employee->airport->city, ['airport/view', 'id' => $model->employee->airport_id]),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="row m-4">
        <div class="col">
            <div class="row h1">Employee Statistics</div>
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => Html::a('Tickets check-in', ['ticket/index', 'employee_id' => $model->id]),
                            'value' => function ($model) {
                                return count($model->employee->tickets);
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
