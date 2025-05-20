<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Producto */

$this->title = 'Actualizar Producto: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id_producto' => $model->id_producto]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="producto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
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
                            <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['/promociones/delete', 'id_promocion' => $promocion->id_promocion], [
                                'title' => 'Eliminar',
                                'data' => [
                                    'confirm' => '¿Estás seguro de que quieres eliminar esta promoción?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <p>
        <?= Html::a('Añadir nueva promoción', ['/promociones/create', 'id_producto' => $model->id_producto], ['class' => 'btn btn-success']) ?>
    </p>
    <?php else: ?>
    <div class="alert alert-info">
        Este producto no tiene promociones asociadas.
        <?= Html::a('Crear nueva promoción', ['/promociones/create', 'id_producto' => $model->id_producto], ['class' => 'btn btn-success btn-sm pull-right']) ?>
    </div>
    <?php endif; ?>

</div>