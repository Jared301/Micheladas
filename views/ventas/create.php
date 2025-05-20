<?php
use yii\helpers\Html;

/* @var $model app\models\Venta */
/* @var $productos app\models\Producto[] */
/* @var $empleados app\models\Empleado[] */

$this->title = 'Crear Venta';
?>
<div class="venta-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model'     => $model,
        'productos' => $productos,
        'empleados' => $empleados,
    ]) ?>
</div>
