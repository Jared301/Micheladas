<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cuenta */

$this->title = 'Actualizar Cuenta: ' . $model->id_cuenta;
$this->params['breadcrumbs'][] = ['label' => 'Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_cuenta, 'url' => ['view', 'id' => $model->id_cuenta]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="cuenta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>