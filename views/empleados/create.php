<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\Models\Empleado $model */

$this->title = 'Create Empleados';
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empleados-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
