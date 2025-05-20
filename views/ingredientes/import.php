<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Ingredientes desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingrediente-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="ingrediente-import-form">
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
                <code>nombre,tipo,unidad_medida,id_proveedor</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
nombre,tipo,unidad_medida,id_proveedor
Limón,Cítrico,unidad,1
Sal,Condimento,gramos,2
Azúcar,Endulzante,gramos,2
Agua Mineral,Bebida,litros,3
</pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado con los nombres de columna</li>
                <li>El campo <strong>nombre</strong> es obligatorio</li>
                <li>Los campos <strong>tipo</strong>, <strong>unidad_medida</strong> y <strong>id_proveedor</strong> son opcionales</li>
                <li>El archivo debe estar en formato UTF-8 para manejar correctamente caracteres especiales</li>
            </ul>
        </div>
    </div>

</div>