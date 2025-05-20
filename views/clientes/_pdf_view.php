<?php
/* @var $clientes app\models\Cliente[] */
use yii\helpers\Html;
?>

<div class="cliente-pdf">
    <h1>Lista de Clientes</h1>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= $cliente->id_cliente ?></td>
                <td><?= Html::encode($cliente->nombre) ?></td>
                <td><?= Html::encode($cliente->apellido) ?></td>
                <td><?= Html::encode($cliente->telefono) ?></td>
                <td><?= Html::encode($cliente->correo_electronico) ?></td>
                <td><?= Html::encode($cliente->direccion) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>