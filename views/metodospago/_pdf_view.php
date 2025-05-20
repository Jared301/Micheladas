<?php
/* @var $metodosPago app\models\Metodospago[] */
?>

<div class="metodospago-pdf">
    <h1 class="kv-heading-1">Listado de Métodos de Pago</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($metodosPago as $metodoPago): ?>
            <tr>
                <td><?= $metodoPago->id_metodo_pago ?></td>
                <td><?= $metodoPago->tipo ?></td>
                <td><?= $metodoPago->detalles ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>