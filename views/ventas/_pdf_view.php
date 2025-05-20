<?php
/* @var $ventas app\models\Venta[] */
/* @var $mostrarProductos boolean */

// Si no se define $mostrarProductos, asumimos que es true
$mostrarProductos = isset($mostrarProductos) ? $mostrarProductos : true;
?>

<div class="venta-pdf">
    <h1 class="kv-heading-1">Reporte de Ventas</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Empleado</th>
                <th>Método de Pago</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?= $venta->id_venta ?></td>
                <td><?= Yii::$app->formatter->asDatetime($venta->fecha) ?></td>
                <td><?= $venta->cliente ? $venta->cliente->nombre . ' ' . $venta->cliente->apellido : 'No asignado' ?></td>
                <td><?= $venta->empleado ? $venta->empleado->nombre . ' ' . $venta->empleado->apellido : 'No asignado' ?></td>
                <td><?= $venta->metodoPago ? $venta->metodoPago->tipo : 'No asignado' ?></td>
                <td><?= Yii::$app->formatter->asCurrency($venta->total) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if ($mostrarProductos): ?>
    <?php foreach ($ventas as $venta): ?>
    <div class="venta-header">
        <p>Venta #<?= $venta->id_venta ?> - <?= Yii::$app->formatter->asDatetime($venta->fecha) ?> - Total: <?= Yii::$app->formatter->asCurrency($venta->total) ?></p>
    </div>
    
    <?php if (!empty($venta->cuentas)): ?>
    <p class="productos-title">Productos:</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($venta->cuentas as $cuenta): ?>
            <tr>
                <td><?= $cuenta->id_cuenta ?></td>
                <td><?= $cuenta->producto ? $cuenta->producto->nombre : 'Producto no disponible' ?></td>
                <td><?= $cuenta->producto ? Yii::$app->formatter->asCurrency($cuenta->producto->precio) : 'N/A' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>Esta venta no tiene productos registrados.</p>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>