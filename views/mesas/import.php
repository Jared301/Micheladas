<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Mesas desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Mesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mesa-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="mesa-import-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'csvFile')->fileInput() ?>

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
                <code>numero_mesa,capacidad,estado,id_empleado_o_correo</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
numero_mesa,capacidad,estado,id_empleado_o_correo
1,4,Disponible,1
2,6,Ocupada,juan@ejemplo.com
3,2,Reservada,maria@ejemplo.com
            </pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado (será ignorada)</li>
                <li>En la columna id_empleado_o_correo puedes especificar el ID del empleado o su correo electrónico</li>
                <li>Los estados válidos son: Disponible, Ocupada, Reservada, Mantenimiento</li>
            </ul>
        </div>
    </div>

</div>