<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProductoIngrediente */

$this->title = 'Crear Relación Producto-Ingrediente';
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes por Producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producto-ingrediente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>