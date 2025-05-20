<?php
/* @var $promociones app\models\Promocion[] */
?>

<div class="promocion-pdf">
    <h1 class="kv-heading-1">Listado de Promociones</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Descuento</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Producto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promociones as $promocion): ?>
            <tr>
                <td><?= $promocion->id_promocion ?></td>
                <td><?= $promocion->nombre ?></td>
                <td><?= $promocion->descripcion ?></td>
                <td><?= $promocion->descuento ?>%</td>
                <td><?= Yii::$app->formatter->asDate($promocion->fecha_inicio) ?></td>
                <td><?= Yii::$app->formatter->asDate($promocion->fecha_fin) ?></td>
                <td>
                    <?= $promocion->producto ? $promocion->producto->nombre : 'Sin asignar' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>