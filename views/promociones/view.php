<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Promocion */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Promociones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="promocion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id_promocion' => $model->id_promocion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id_promocion' => $model->id_promocion], [
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
            'id_promocion',
            'nombre',
            'descripcion:ntext',
            [
                'attribute' => 'descuento',
                'value' => function ($model) {
                    return $model->descuento . '%';
                },
            ],
            'fecha_inicio:date',
            'fecha_fin:date',
            [
                'attribute' => 'id_producto',
                'value' => $model->producto ? $model->producto->nombre : 'Sin asignar',
                'label' => 'Producto'
            ],
        ],
    ]) ?>

</div>