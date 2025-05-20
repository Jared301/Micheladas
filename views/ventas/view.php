<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $model app\models\Venta */
/* @var $detalles app\models\DetalleVenta[] */

$this->title = 'Venta #'.$model->id_venta;
?>

<p>
    <?= Html::a('Actualizar', ['update', 'id' => $model->id_venta], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Eliminar',   ['delete',   'id' => $model->id_venta], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => '¿Seguro que deseas eliminar esta venta?',
            'method'  => 'post',
        ],
    ]) ?>
    <!-- Botón para generar ticket -->
    <?= Html::a('Imprimir Ticket', ['ticket', 'id' => $model->id_venta], [
        'class'   => 'btn btn-success',
        'target'  => '_blank',
        'data-pjax'=> '0',
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $model,
    'attributes' => [
        'id_venta',
        'fecha:datetime',
        'total:currency',
        [
            'attribute' => 'id_cliente',
            'value'     => $model->cliente ? $model->cliente->nombre.' '.$model->cliente->apellido : null,
        ],
        [
            'attribute' => 'id_empleado',
            'value'     => $model->empleado ? $model->empleado->nombre.' '.$model->empleado->apellido : null,
        ],

    ],
]) ?>

<h3>Detalles</h3>
<table class="table table-striped">
    <thead>
        <tr><th>Producto</th><th>Cantidad</th><th>Subtotal</th></tr>
    </thead>
    <tbody>
    <?php foreach ($detalles as $d): ?>
        <tr>
            <td><?= Html::encode($d->producto->nombre) ?></td>
            <td><?= $d->cantidad ?></td>
            <td><?= Yii::$app->formatter->asCurrency($d->cantidad * $d->precio_unitario) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
