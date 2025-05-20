<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Cuentas desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuenta-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="cuenta-import-form">
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
                <code>id_venta,id_producto</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
id_venta,id_producto
1,1
1,3
1,5
2,2
2,4
3,1
3,3
3,7
</pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado con los nombres de columna</li>
                <li>Los campos <strong>id_venta</strong> e <strong>id_producto</strong> son obligatorios</li>
                <li>Los IDs de venta y producto deben corresponder a registros existentes en la base de datos</li>
                <li>Cada fila representa un producto que se incluye en una venta específica</li>
                <li>Se pueden incluir múltiples productos para la misma venta usando diferentes filas</li>
            </ul>
        </div>
    </div>

</div>