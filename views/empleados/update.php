<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Actualizar Empleado: ' . $model->nombre . ' ' . $model->apellido;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre . ' ' . $model->apellido, 'url' => ['view', 'id' => $model->id_empleado]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="empleado-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>