<?php
/* @var $mesas app\models\Mesa[] */
?>

<div class="mesa-pdf">
    <h1 class="kv-heading-1">Listado de Mesas</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Mesa</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Empleado Asignado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mesas as $mesa): ?>
            <tr>
                <td><?= $mesa->id_mesa ?></td>
                <td><?= $mesa->numero_mesa ?></td>
                <td><?= $mesa->capacidad ?></td>
                <td><?= $mesa->estado ?></td>
                <td>
                    <?= $mesa->empleado ? $mesa->empleado->nombre . ' ' . $mesa->empleado->apellido : 'Sin asignar' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>