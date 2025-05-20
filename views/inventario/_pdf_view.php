<?php
/* @var $inventarios app\models\Inventario[] */
?>

<div class="inventario-pdf">
    <h1 class="kv-heading-1">Inventario Actual</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ingrediente</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Última Actualización</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventarios as $inventario): ?>
            <tr>
                <td><?= $inventario->id_inventario ?></td>
                <td><?= $inventario->ingrediente ? $inventario->ingrediente->nombre : 'No asignado' ?></td>
                <td><?= $inventario->cantidad_actual ?></td>
                <td><?= $inventario->ingrediente ? $inventario->ingrediente->unidad_medida : '' ?></td>
                <td><?= Yii::$app->formatter->asDatetime($inventario->fecha_actualizacion) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>