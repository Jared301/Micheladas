<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ingrediente */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ingrediente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_ingrediente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_ingrediente], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_ingrediente',
            'nombre',
            'tipo',
            'unidad_medida',
            [
                'attribute' => 'id_proveedor',
                'value' => $model->proveedor ? $model->proveedor->nombre_empresa : 'No asignado',
            ],
        ],
    ]) ?>

    <h3>Inventario Actual</h3>
    
    <?php if (!empty($model->inventarios)): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cantidad</th>
                    <th>Última Actualización</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->inventarios as $inventario): ?>
                <tr>
                    <td><?= $inventario->id_inventario ?></td>
                    <td><?= $inventario->cantidad_actual ?> <?= $model->unidad_medida ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($inventario->fecha_actualizacion) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        Este ingrediente no tiene registros de inventario.
        <?= Html::a('Agregar al inventario', ['/inventario/create', 'id_ingrediente' => $model->id_ingrediente], ['class' => 'btn btn-success btn-sm pull-right']) ?>
    </div>
    <?php endif; ?>

</div>