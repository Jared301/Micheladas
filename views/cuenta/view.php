<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cuenta */

$this->title = 'Cuenta #' . $model->id_cuenta;
$this->params['breadcrumbs'][] = ['label' => 'Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cuenta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_cuenta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_cuenta], [
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
            'id_cuenta',
            [
                'attribute' => 'id_venta',
                'value' => $model->venta ? 'Venta #' . $model->id_venta . ' (' . Yii::$app->formatter->asDatetime($model->venta->fecha) . ')' : 'No disponible',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->venta) {
                        return Html::a('Venta #' . $model->id_venta . ' (' . Yii::$app->formatter->asDatetime($model->venta->fecha) . ')', ['/ventas/view', 'id' => $model->id_venta]);
                    }
                    return 'No disponible';
                },
            ],
            [
                'attribute' => 'id_producto',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->producto) {
                        return Html::a($model->producto->nombre, ['/producto/view', 'id_producto' => $model->id_producto]);
                    }
                    return 'No disponible';
                },
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
            </dl>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($model->venta): ?>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Información de la Venta</h3>
        </div>
        <div class="panel-body">
            <dl class="dl-horizontal">
                <dt>Fecha:</dt>
                <dd><?= Yii::$app->formatter->asDatetime($model->venta->fecha) ?></dd>
                
                <dt>Total:</dt>
                <dd><?= Yii::$app->formatter->asCurrency($model->venta->total) ?></dd>
                
                <dt>Cliente:</dt>
                <dd><?= $model->venta->cliente ? $model->venta->cliente->nombre . ' ' . $model->venta->cliente->apellido : 'No asignado' ?></dd>
                
                <dt>Empleado:</dt>
                <dd><?= $model->venta->empleado ? $model->venta->empleado->nombre . ' ' . $model->venta->empleado->apellido : 'No asignado' ?></dd>
                
                <dt>Método de Pago:</dt>
                <dd><?= $model->venta->metodoPago ? $model->venta->metodoPago->tipo : 'No asignado' ?></dd>
            </dl>
            
            <?= Html::a('Ver Detalles de la Venta', ['/ventas/view', 'id' => $model->id_venta], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <?php endif; ?>

</div>