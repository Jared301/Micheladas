<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ingrediente */

$this->title = 'Actualizar Ingrediente: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id_ingrediente]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ingrediente-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>