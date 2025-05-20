<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Promocion */

$this->title = 'Actualizar Promoción: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Promociones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id_promocion' => $model->id_promocion]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="promocion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>