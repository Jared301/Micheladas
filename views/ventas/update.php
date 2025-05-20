<?php
use yii\helpers\Html;

/* @var $model app\models\Venta */
/* @var $productos app\models\Producto[] */
/* @var $empleados app\models\Empleado[] */
/* @var $detalles app\models\DetalleVenta[] */

$this->title = 'Actualizar Venta #' . $model->id_venta;
?>
<div class="venta-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model'     => $model,
        'productos' => $productos,
        'empleados' => $empleados,
        'detalles'  => $detalles,
    ]) ?>
</div>
