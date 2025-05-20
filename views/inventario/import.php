<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Inventario desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Inventario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventario-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="inventario-import-form">
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
                <code>id_ingrediente,cantidad_actual</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
id_ingrediente,cantidad_actual
1,50
2,100
3,25.5
4,10
</pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado con los nombres de columna</li>
                <li>Los campos <strong>id_ingrediente</strong> y <strong>cantidad_actual</strong> son obligatorios</li>
                <li>El campo <strong>id_ingrediente</strong> debe corresponder a un ingrediente existente en la base de datos</li>
                <li>Si ya existe un registro de inventario para el ingrediente, se actualizará la cantidad</li>
                <li>Para valores decimales, utiliza punto (.) como separador decimal, no coma</li>
                <li>La fecha de actualización se establecerá automáticamente al momento de la importación</li>
            </ul>
        </div>
    </div>

</div>