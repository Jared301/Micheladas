<?php
/* @var $empleados app\models\Empleado[] */
/* @var $mostrarMesas boolean */

// Si no se define $mostrarMesas, asumimos que es false
$mostrarMesas = isset($mostrarMesas) ? $mostrarMesas : false;
?>

<div class="empleado-pdf">
    <h1 class="kv-heading-1">Listado de Empleados</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Puesto</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Salario</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
            <tr>
                <td><?= $empleado->id_empleado ?></td>
                <td><?= $empleado->nombre ?></td>
                <td><?= $empleado->apellido ?></td>
                <td><?= $empleado->puesto ?></td>
                <td><?= $empleado->telefono ?></td>
                <td><?= $empleado->correo_electronico ?></td>
                <td><?= Yii::$app->formatter->asCurrency($empleado->salario) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if ($mostrarMesas): ?>
    <!-- Esta sección solo se mostrará si $mostrarMesas es true -->
    <h3>Detalle de Mesas Asignadas</h3>
    
    <?php foreach ($empleados as $empleado): ?>
    <div class="empleado-info <?= !$empleado === reset($empleados) ? 'page-break' : '' ?>">
        <h4><?= $empleado->nombre . ' ' . $empleado->apellido ?> (ID: <?= $empleado->id_empleado ?>)</h4>
        
        <?php if (!empty($empleado->mesas)): ?>
        <table>
            <thead>
                <tr>
                    <th>Número Mesa</th>
                    <th>Capacidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleado->mesas as $mesa): ?>
                <tr>
                    <td><?= $mesa->numero_mesa ?></td>
                    <td><?= $mesa->capacidad ?></td>
                    <td><?= $mesa->estado ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Este empleado no tiene mesas asignadas.</p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>