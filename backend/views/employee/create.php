<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Employee $model */

$this->title = 'Create Employee';
?>
<div class="employee-create">


    <?= $this->render('_form', [
        'model' => $model,
        'airports' => $airports,
        'roles' => $roles,
    ]) ?>

</div>
