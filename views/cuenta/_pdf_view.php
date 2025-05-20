<?php
/* @var $cuentas app\models\Cuenta[] */
?>

<div class="cuenta-pdf">
    <h1 class="kv-heading-1">Detalle de Cuentas</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Venta</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cuentas as $cuenta): ?>
            <tr>
                <td><?= $cuenta->id_cuenta ?></td>
                <td>Venta #<?= $cuenta->id_venta ?></td>
                <td><?= $cuenta->venta ? Yii::$app->formatter->asDatetime($cuenta->venta->fecha) : 'No disponible' ?></td>
                <td><?= $cuenta->producto ? $cuenta->producto->nombre : 'No disponible' ?></td>
                <td><?= $cuenta->producto ? Yii::$app->formatter->asCurrency($cuenta->producto->precio) : 'N/A' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>