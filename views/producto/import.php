<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Productos desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producto-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="producto-import-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'csvFile')->fileInput(['accept' => '.csv'])->hint('Selecciona un archivo CSV para importar') ?>

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
                <code>nombre,descripcion,precio,stock,tamaño</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
nombre,descripcion,precio,stock,tamaño
Michelada Tradicional,Cerveza preparada con chamoy y chile,60.00,15,Grande
Michelada Mango,Cerveza preparada con mango y tajín,70.00,12,Grande
Chelada,Cerveza con limón y sal,50.00,20,Mediana
</pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado</li>
                <li>Los campos <strong>nombre</strong>, <strong>descripcion</strong> y <strong>precio</strong> son obligatorios</li>
                <li>Los campos <strong>stock</strong> y <strong>tamaño</strong> son opcionales</li>
                <li>Si no se especifica <strong>stock</strong>, se asignará 0 por defecto</li>
                <li>Los precios deben ser números (pueden incluir decimales)</li>
                <li>Para valores decimales, utiliza punto (.) como separador decimal, no coma</li>
                <li>El archivo debe estar en formato UTF-8 para manejar correctamente caracteres especiales</li>
            </ul>
        </div>
    </div>

</div>