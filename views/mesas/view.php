<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mesa */

$this->title = 'Mesa #' . $model->numero_mesa;
$this->params['breadcrumbs'][] = ['label' => 'Mesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mesa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_mesa], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_mesa], [
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
            'id_mesa',
            'numero_mesa',
            'capacidad',
            'estado',
            [
                'attribute' => 'id_empleado',
                'value' => $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'Sin asignar',
                'label' => 'Empleado'
            ],
        ],
    ]) ?>

</div>