<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Config $model */

$this->title = 'Create Config';
?>
<div class="config-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
