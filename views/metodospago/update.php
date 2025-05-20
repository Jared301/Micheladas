<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Metodospago */

$this->title = 'Actualizar Método de Pago: ' . $model->tipo;
$this->params['breadcrumbs'][] = ['label' => 'Métodos de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tipo, 'url' => ['view', 'id_metodo_pago' => $model->id_metodo_pago]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="metodospago-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>