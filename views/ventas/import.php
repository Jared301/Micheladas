<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Ventas desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Ventas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venta-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="venta-import-form">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'csvFile')->fileInput(['accept' => '.csv'])->hint('Selecciona un archivo CSV para importar') ?>

                <div class="form-group">
                    <?= Html::submitButton('Importar', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Instrucciones</h3>
        </div>
        <div class="panel-body">
            <p>El archivo CSV debe tener el siguiente formato:</p>
            <p>
                <code>fecha,total,id_cliente,id_empleado,id_metodo_pago,productos</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
fecha,total,id_cliente,id_empleado,id_metodo_pago,productos
2023-10-15 15:30:00,250.50,1,3,2,1|3|5
2023-10-16 10:15:00,180.25,2,1,1,2|4|6
2023-10-16 18:45:00,320.00,3,2,3,1|7|8|9
</pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado con los nombres de columna</li>
                <li>Los campos <strong>fecha</strong> y <strong>total</strong> son obligatorios</li>
                <li>La <strong>fecha</strong> debe estar en formato YYYY-MM-DD HH:MM:SS</li>
                <li>El <strong>total</strong> debe ser un número (puede incluir decimales separados por punto)</li>
                <li>Los campos <strong>id_cliente</strong>, <strong>id_empleado</strong> y <strong>id_metodo_pago</strong> son opcionales</li>
                <li>El campo <strong>productos</strong> es opcional y debe contener IDs de productos separados por barras verticales (|)</li>
                <li>Para valores decimales, utiliza punto (.) como separador decimal, no coma</li>
                <li>El archivo debe estar en formato UTF-8 para manejar correctamente caracteres especiales</li>
            </ul>
        </div>
    </div>

</div>