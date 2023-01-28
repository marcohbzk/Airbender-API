<?php

use yii\helpers\Html;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href=".." class="brand-link">
        <span class="brand-text font-weight-light">Airbender</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <?= Html::a(Yii::$app->user->identity->username, ['employee/view', 'user_id' => \Yii::$app->user->identity->getId()]) ?>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            $items = [
                [
                    'label' => 'Dashboard',
                    'icon' => 'tachometer-alt',
                    'url' => ['site/index']
                ],
                ['label' => 'SHORTCUTS', 'header' => true],
                [
                    'label' => 'Create a employee',
                    'icon' => 'fa-solid fa-user-plus',
                    'url' => ['employee/create']
                ],
                [
                    'label' => 'Create a flight',
                    'icon' => 'fa-duotone fa-plane-departure',
                    'url' => ['flight/create']
                ],
                ['label' => 'AIRPORTS', 'header' => true],
                [
                    'label' => 'Employees',
                    'icon' => 'fa-solid fa-user-tie',
                    'url' => ['employee/index']
                ],
                [
                    'label' => 'Luggage',
                    'icon' => 'fa-solid fa-suitcase-rolling',
                    'url' => ['config/index']
                ],
                [
                    'label' => 'Airports',
                    'icon' => 'fa-solid fa-building',
                    'url' => ['airport/index']
                ],
                [
                    'label' => 'Flights',
                    'icon' => 'fa-solid fa-plane-departure',
                    'url' => ['flight/index']
                ],
                [
                    'label' => 'Airplanes',
                    'icon' => 'fa-solid fa-plane',
                    'url' => ['airplane/index']
                ],
                ['label' => 'CUSTOMERS', 'header' => true],
                [
                    'label' => 'Clients',
                    'icon' => 'fa-solid fa-user',
                    'url' => ['client/index']
                ],
                [
                    'label' => 'Balance Requests',
                    'icon' => 'fa-solid fa-comment-dollar',
                    'url' => ['balance-req/index']
                ],
            ];
            if (key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())) == 'supervisor')
                unset($items[1],$items[2], $items[3], $items[5]);

            if (key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())) == 'ticketOperator')
                unset($items[1],$items[2], $items[3], $items[5], $items[9], $items[12], $items[13], $items[14]);

            echo \hail812\adminlte\widgets\Menu::widget(['items' => $items]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
