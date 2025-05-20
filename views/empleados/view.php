<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = $model->nombre . ' ' . $model->apellido;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="empleado-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_empleado], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_empleado], [
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
            'id_empleado',
            'nombre',
            'apellido',
            'puesto',
            'telefono',
            'correo_electronico',
            'salario',
        ],
    ]) ?>

    <?php if (!empty($model->mesas)): ?>
    <h3>Mesas asignadas</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número</th>
                    <th>Capacidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->mesas as $mesa): ?>
                <tr>
                    <td><?= $mesa->id_mesa ?></td>
                    <td><?= $mesa->numero_mesa ?></td>
                    <td><?= $mesa->capacidad ?></td>
                    <td><?= $mesa->estado ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        Este empleado no tiene mesas asignadas.
    </div>
    <?php endif; ?>

</div>