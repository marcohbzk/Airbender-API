<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Employee $model */

$this->title = 'Update Employee: ' . $model->user_id;
?>
<div class="employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'airports' => $airports,
        'roles' => $roles,
    ]) ?>

</div>
