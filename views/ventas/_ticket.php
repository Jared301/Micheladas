<?php
use yii\helpers\Html;

/* @var $venta app\models\Venta */
/* @var $detalles app\models\DetalleVenta[] */
?>
<div class="ticket">
    <h2>Micheladas El Rey</h2>
    <p>
      Ticket #: <?= $venta->id_venta ?><br>
      Fecha: <?= Yii::$app->formatter->asDatetime($venta->fecha) ?><br>
      <!-- Nueva línea para el empleado -->
      Le atendió: <?= $venta->empleado
          ? Html::encode($venta->empleado->nombre . ' ' . $venta->empleado->apellido)
          : 'N/A' ?>
    </p>
    <table>
      <thead>
        <tr><th>Producto</th><th>Cant</th><th>Total</th></tr>
      </thead>
      <tbody>
        <?php foreach($detalles as $d): ?>
        <tr>
          <td><?= Html::encode($d->producto->nombre) ?></td>
          <td><?= $d->cantidad ?></td>
          <td><?= Yii::$app->formatter->asDecimal($d->cantidad * $d->precio_unitario, 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <hr>
    <p style="text-align:right;">
      <strong>Total: <?= Yii::$app->formatter->asDecimal($venta->total, 2) ?></strong>
    </p>
    <p style="text-align:center;">¡Gracias por su compra!</p>
</div>
