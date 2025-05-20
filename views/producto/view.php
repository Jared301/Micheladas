<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Producto */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="producto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id_producto' => $model->id_producto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id_producto' => $model->id_producto], [
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
            'id_producto',
            'nombre',
            'descripcion:ntext',
            [
                'attribute' => 'precio',
                'format' => 'currency',
            ],
            'stock',
            'tamaño',
            'created_at:datetime',
        ],
    ]) ?>

    <?php if (isset($model->promociones) && !empty($model->promociones)): ?>
    <h3>Promociones asociadas</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Descuento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->promociones as $promocion): ?>
                <tr>
                    <td><?= $promocion->id_promocion ?></td>
                    <td><?= $promocion->nombre ?></td>
                    <td><?= $promocion->descripcion ?></td>
                    <td><?= $promocion->descuento ?>%</td>
                    <td><?= Yii::$app->formatter->asDate($promocion->fecha_inicio) ?></td>
                    <td><?= Yii::$app->formatter->asDate($promocion->fecha_fin) ?></td>
                    <td>
                        <?= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['/promociones/view', 'id_promocion' => $promocion->id_promocion], ['title' => 'Ver']) ?>
                        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/promociones/update', 'id_promocion' => $promocion->id_promocion], ['title' => 'Actualizar']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        Este producto no tiene promociones asociadas.
        <?= Html::a('Crear nueva promoción', ['/promociones/create', 'id_producto' => $model->id_producto], ['class' => 'btn btn-success btn-sm pull-right']) ?>
    </div>
    <?php endif; ?>

</div>