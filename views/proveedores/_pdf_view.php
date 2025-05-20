<?php
/* @var $proveedores app\models\Proveedor[] */
?>

<div class="proveedor-pdf">
    <h1>Lista de Proveedores</h1>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Empresa</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proveedores as $proveedor): ?>
            <tr>
                <td><?= $proveedor->id_proveedor ?></td>
                <td><?= $proveedor->nombre_empresa ?></td>
                <td><?= $proveedor->contacto ?? '-' ?></td>
                <td><?= $proveedor->telefono ?? '-' ?></td>
                <td><?= $proveedor->correo_electronico ?? '-' ?></td>
                <td><?= $proveedor->direccion ?? '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>