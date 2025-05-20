<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */

$this->title = 'Inventario: ' . ($model->ingrediente ? $model->ingrediente->nombre : 'No asignado');
$this->params['breadcrumbs'][] = ['label' => 'Inventario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="inventario-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_inventario], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_inventario], [
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
            'id_inventario',
            [
                'attribute' => 'id_ingrediente',
                'value' => $model->ingrediente ? $model->ingrediente->nombre : 'No asignado',
            ],
            [
                'attribute' => 'cantidad_actual',
                'value' => $model->cantidad_actual . ' ' . ($model->ingrediente ? $model->ingrediente->unidad_medida : ''),
            ],
            'fecha_actualizacion:datetime',
        ],
    ]) ?>

    <?php if ($model->ingrediente): ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Información del Ingrediente</h3>
        </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>Nombre:</dt>
                <dd><?= $model->ingrediente->nombre ?></dd>
                
                <dt>Tipo:</dt>
                <dd><?= $model->ingrediente->tipo ?></dd>
                
                <dt>Unidad de Medida:</dt>
                <dd><?= $model->ingrediente->unidad_medida ?></dd>
                
                <dt>Proveedor:</dt>
                <dd><?= $model->ingrediente->proveedor ? $model->ingrediente->proveedor->nombre_empresa : 'No asignado' ?></dd>
            </dl>
            
            <?= Html::a('Ver Detalles del Ingrediente', ['/ingredientes/view', 'id' => $model->id_ingrediente], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <?php endif; ?>

</div>