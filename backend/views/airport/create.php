<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Airport $model */

$this->title = 'Create Airport';
?>
<div class="airport-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
