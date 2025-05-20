<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */

$this->title = 'Actualizar Inventario: ' . ($model->ingrediente ? $model->ingrediente->nombre : 'No asignado');
$this->params['breadcrumbs'][] = ['label' => 'Inventario', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($model->ingrediente ? $model->ingrediente->nombre : 'No asignado'), 'url' => ['view', 'id' => $model->id_inventario]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="inventario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>