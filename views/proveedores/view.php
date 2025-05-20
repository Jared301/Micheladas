<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\Models\Proveedores $model */

$this->title = $model->nombre_empresa;
$this->params['breadcrumbs'][] = ['label' => 'Proveedores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="proveedor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_proveedor' => $model->id_proveedor], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_proveedor' => $model->id_proveedor], [
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
            'id_proveedor',
            'nombre_empresa',
            'contacto',
            'telefono',
            'correo_electronico:email',
            'direccion',
        ],
    ]) ?>

</div>