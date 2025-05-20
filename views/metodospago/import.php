<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Métodos de Pago desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Métodos de Pago', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodospago-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="metodospago-import-form">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ]
                ]); ?>

                <?= $form->field($model, 'csvFile')->fileInput([
                    'accept' => '.csv',
                    'class' => 'form-control',
                ])->hint('Selecciona un archivo con extensión .csv') ?>

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
                <code>tipo,detalles</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
tipo,detalles
Tarjeta de Crédito,Aceptamos Visa y Mastercard
Efectivo,Pago al recibir el producto
PayPal,Pago en línea a través de PayPal
Transferencia Bancaria,Datos bancarios proporcionados tras la orden
</pre>
            <p><strong>Requisitos importantes:</strong></p>
            <ul>
                <li>El archivo <strong>debe</strong> tener la extensión .csv (no .CSV, .txt, .xls, etc.)</li>
                <li>La primera línea debe ser el encabezado con los nombres de columna</li>
                <li>El campo <strong>tipo</strong> es obligatorio</li>
                <li>El campo <strong>detalles</strong> es opcional</li>
                <li>El archivo debe estar en formato UTF-8 para manejar correctamente caracteres especiales</li>
                <li>Los campos deben estar separados por comas (,)</li>
                <li>Si un campo contiene comas, debe estar entre comillas dobles</li>
            </ul>
            <p><strong>Sugerencia:</strong> Si tienes problemas con el formato, abre tu archivo en un editor de texto como Notepad++ para verificar que cumple con los requisitos.</p>
        </div>
    </div>

</div>