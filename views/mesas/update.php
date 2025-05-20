<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mesa */

$this->title = 'Actualizar Mesa: ' . $model->id_mesa;
$this->params['breadcrumbs'][] = ['label' => 'Mesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_mesa, 'url' => ['view', 'id' => $model->id_mesa]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="mesa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>