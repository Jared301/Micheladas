<?php
/* @var $ingredientes app\models\Ingrediente[] */
?>

<div class="ingrediente-pdf">
    <h1 class="kv-heading-1">Listado de Ingredientes</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Unidad de Medida</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ingredientes as $ingrediente): ?>
            <tr>
                <td><?= $ingrediente->id_ingrediente ?></td>
                <td><?= $ingrediente->nombre ?></td>
                <td><?= $ingrediente->tipo ?></td>
                <td><?= $ingrediente->unidad_medida ?></td>
                <td><?= $ingrediente->proveedor ? $ingrediente->proveedor->nombre_empresa : 'No asignado' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>