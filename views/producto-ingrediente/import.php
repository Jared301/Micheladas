<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Relaciones Producto-Ingrediente desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Ingredientes por Producto', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producto-ingrediente-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('errorDetails')): ?>
    <div class="alert alert-warning">
        <?= Yii::$app->session->getFlash('errorDetails') ?>
    </div>
    <?php endif; ?>

    <div class="producto-ingrediente-import-form">
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
                <code>id_producto,id_ingrediente,cantidad</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
id_producto,id_ingrediente,cantidad
2,7,1
2,4,15
2,5,5
3,6,20
3,4,10
3,5,5
4,8,1
4,4,15
5,4,25
5,5,10
</pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado con los nombres de columna</li>
                <li>Los campos <strong>id_producto</strong> e <strong>id_ingrediente</strong> son obligatorios</li>
                <li>El campo <strong>cantidad</strong> es opcional</li>
                <li>Los IDs deben corresponder a productos e ingredientes existentes en la base de datos</li>
                <li>Si ya existe una relación entre un producto y un ingrediente, se actualizará la cantidad</li>
                <li>Para valores decimales, utiliza punto (.) como separador decimal, no coma</li>
            </ul>
        </div>
    </div>

</div>