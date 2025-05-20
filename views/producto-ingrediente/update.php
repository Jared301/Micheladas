<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductoIngrediente */

$productoNombre = $model->producto ? $model->producto->nombre : 'No disponible';
$ingredienteNombre = $model->ingrediente ? $model->ingrediente->nombre : 'No disponible';

$this->title = 'Actualizar Relación: ' . $productoNombre . ' - ' . $ingredienteNombre;
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes por Producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $productoNombre . ' - ' . $ingredienteNombre, 'url' => ['view', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="producto-ingrediente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>