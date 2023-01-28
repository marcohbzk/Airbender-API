<?php

use common\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'My Profile';
?>
<div class="client-index">
    <div class="gtco-section">
        <div class="gtco-container">
            <div class="row">
                <div class="col-4 text-center align-self-center">
                    <h1><i class='fas fa-fa-solid fa-user'></i></h1>
                    <h3><?= $client->user->userData->fName . ' ' . $client->user->userData->surname ?></h3>
                    <h3><?= $client->user->email ?></h3>
                    <h3><?= $client->user->userData->phone ?></h3>

                    <?= Html::a('Edit profile', ['update', 'user_id' => $client->user_id], ['class' => 'btn btn-success']) ?>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col-6">
                            <h2>
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <h3>My tickets</h3>
                        </div>
                        <div class="col">
                            <?= Html::a('See more', ['ticket/index']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <h3>My balance</h3>
                        </div>
                        <div class="col">
                            <?= Html::a('See more', ['balance-req/index']) ?>
                        </div>
                        <h4> <?= $client->balance ?>€</h4>


                    </div>


                </div>
            </div>
        </div>
    </div>

</div>
