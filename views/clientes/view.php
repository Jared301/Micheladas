<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\Models\Cliente $model */

$this->title = $model->nombre . ' ' . $model->apellido;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cliente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_cliente' => $model->id_cliente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_cliente' => $model->id_cliente], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_cliente',
            'nombre',
            'apellido',
            'telefono',
            'correo_electronico',
            'direccion:ntext',
        ],
    ]) ?>

</div>