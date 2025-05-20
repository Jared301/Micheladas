<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Empleados desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empleado-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="empleado-import-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'csvFile')->fileInput() ?>
        
        <?= $form->field($model, 'importarMesas')->checkbox(['label' => 'Importar también mesas asignadas']) ?>

        <div class="form-group">
            <?= Html::submitButton('Importar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Instrucciones</h3>
        </div>
        <div class="panel-body">
            <p>El archivo CSV debe tener el siguiente formato:</p>
            <p>
                <code>nombre,apellido,puesto,telefono,correo_electronico,salario,mesas</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
nombre,apellido,puesto,telefono,correo_electronico,salario,mesas
Juan,Pérez,Gerente,5551234567,juan@ejemplo.com,25000.00,1:4:Disponible,2:6:Ocupada
María,López,Mesero,5559876543,maria@ejemplo.com,15000.00,3:2:Reservada
Pedro,Gómez,Bartender,5554567890,pedro@ejemplo.com,18000.00,
            </pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado</li>
                <li>La columna 'mesas' tiene un formato especial: numero_mesa:capacidad:estado,numero_mesa:capacidad:estado</li>
                <li>Esta columna puede quedar vacía si el empleado no tiene mesas asignadas</li>
                <li>Marque la casilla "Importar también mesas asignadas" si desea importar las mesas</li>
            </ul>
        </div>
    </div>

</div>