<?php
/* @var $productos app\models\Producto[] */
?>

<div class="producto-pdf">
    <h1 class="kv-heading-1">Listado de Productos</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Tamaño</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto->id_producto ?></td>
                <td><?= $producto->nombre ?></td>
                <td><?= $producto->descripcion ?></td>
                <td><?= Yii::$app->formatter->asCurrency($producto->precio) ?></td>
                <td><?= $producto->stock ?></td>
                <td><?= isset($producto->tamaño) ? $producto->tamaño : (isset($producto->tamano) ? $producto->tamano : '') ?></td>
                <td><?= Yii::$app->formatter->asDate($producto->created_at) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>