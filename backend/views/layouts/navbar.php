<?php

use yii\helpers\Html;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= \yii\helpers\Url::home() ?>" class="nav-link">Home</a>
        </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::button('<i class="fas fa-sign-out-alt"></i>', ['data-href' => 'Logout', 'data-toggle' => 'modal', 'data-target' => '#confirm-delete', 'class' => 'btn btn-link nav-link']) ?>
        </li>
    </ul>
</nav>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Logout
            </div>
            <div class="modal-body">
                Are you sure you want to Logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <?= Html::a('Logout', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-danger btn-ok nav-link']) ?>
            </div>
        </div>
    </div>
</div>
<!-- /.navbar -->
