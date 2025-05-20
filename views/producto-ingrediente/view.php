<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductoIngrediente */

$productoNombre = $model->producto ? $model->producto->nombre : 'No disponible';
$ingredienteNombre = $model->ingrediente ? $model->ingrediente->nombre : 'No disponible';

$this->title = $productoNombre . ' - ' . $ingredienteNombre;
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes por Producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="producto-ingrediente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente], [
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
            [
                'attribute' => 'id_producto',
                'value' => $model->producto ? $model->producto->nombre . ' (ID: ' . $model->id_producto . ')' : 'No disponible',
            ],
            [
                'attribute' => 'id_ingrediente',
                'value' => $model->ingrediente ? $model->ingrediente->nombre . ' (ID: ' . $model->id_ingrediente . ')' : 'No disponible',
            ],
            [
                'attribute' => 'cantidad',
                'value' => $model->cantidad . ' ' . ($model->ingrediente ? $model->ingrediente->unidad_medida : ''),
            ],
        ],
    ]) ?>

    <?php if ($model->producto): ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Información del Producto</h3>
        </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>Nombre:</dt>
                <dd><?= $model->producto->nombre ?></dd>
                
                <dt>Descripción:</dt>
                <dd><?= $model->producto->descripcion ?></dd>
                
                <dt>Precio:</dt>
                <dd><?= Yii::$app->formatter->asCurrency($model->producto->precio) ?></dd>
                
                <dt>Stock:</dt>
                <dd><?= $model->producto->stock ?></dd>
                
                <dt>Tamaño:</dt>
                <dd><?= $model->producto->tamaño ?></dd>
            </dl>
            
            <?= Html::a('Ver Producto', ['/producto/view', 'id_producto' => $model->id_producto], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($model->ingrediente): ?>
    <div class="panel panel-success">
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
            
            <?= Html::a('Ver Ingrediente', ['/ingredientes/view', 'id' => $model->id_ingrediente], ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php endif; ?>

</div>