<?php
/* @var $models app\models\ProductoIngrediente[] */

// Agrupar los ingredientes por producto
$productoIngredientes = [];
foreach ($models as $model) {
    $idProducto = $model->id_producto;
    if (!isset($productoIngredientes[$idProducto])) {
        $productoIngredientes[$idProducto] = [
            'producto' => $model->producto,
            'ingredientes' => []
        ];
    }
    $productoIngredientes[$idProducto]['ingredientes'][] = $model;
}
?>

<div class="producto-ingrediente-pdf">
    <h1 class="kv-heading-1">Listado de Ingredientes por Producto</h1>
    
    <?php foreach ($productoIngredientes as $idProducto => $data): ?>
    <?php $producto = $data['producto']; ?>
    
    <h2><?= $producto ? $producto->nombre : 'Producto ID: ' . $idProducto ?></h2>
    
    <?php if ($producto): ?>
    <p>
        <strong>Descripción:</strong> <?= $producto->descripcion ?><br>
        <strong>Precio:</strong> <?= Yii::$app->formatter->asCurrency($producto->precio) ?><br>
        <strong>Tamaño:</strong> <?= $producto->tamaño ?>
    </p>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ingrediente</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Unidad</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['ingredientes'] as $model): ?>
            <?php $ingrediente = $model->ingrediente; ?>
            <tr>
                <td><?= $model->id_ingrediente ?></td>
                <td><?= $ingrediente ? $ingrediente->nombre : 'No disponible' ?></td>
                <td><?= $ingrediente ? $ingrediente->tipo : 'N/A' ?></td>
                <td><?= $model->cantidad ?></td>
                <td><?= $ingrediente ? $ingrediente->unidad_medida : 'N/A' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="page-break"></div>
    <?php endforeach; ?>
    
    <div>
        <p>Generado el: <?= date('Y-m-d H:i:s') ?></p>
    </div>
</div>