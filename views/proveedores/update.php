<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\Models\Proveedores $model */

$this->title = 'Update Proveedor: ' . $model->nombre_empresa;
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_empresa, 'url' => ['view', 'id_proveedor' => $model->id_proveedor]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="proveedor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>