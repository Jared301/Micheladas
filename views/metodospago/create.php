<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\Models\Metodospago $model */

$this->title = 'Crear Método de Pago';
$this->params['breadcrumbs'][] = ['label' => 'Métodos de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodospago-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>