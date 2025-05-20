<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\base\DynamicModel */

$this->title = 'Importar Promociones desde CSV';
$this->params['breadcrumbs'][] = ['label' => 'Promociones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocion-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="promocion-import-form">

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
                <code>nombre,descripcion,descuento,fecha_inicio,fecha_fin,id_producto_o_nombre</code>
            </p>
            <p>Ejemplo:</p>
            <pre>
nombre,descripcion,descuento,fecha_inicio,fecha_fin,id_producto_o_nombre
Descuento verano,20% de descuento,20,2023-06-01,2023-08-31,Michelada Tradicional
Oferta 2x1,Lleva 2 paga 1,50,2023-07-15,2023-07-31,Michelada Mango
Promoción fin de año,10% de descuento,10,2023-12-01,2023-12-31,5
            </pre>
            <p>Notas:</p>
            <ul>
                <li>La primera línea debe ser el encabezado</li>
                <li>En la columna id_producto_o_nombre puedes especificar el ID o el nombre del producto</li>
                <li>Las fechas deben estar en formato YYYY-MM-DD</li>
                <li>El descuento debe ser un número (representa el porcentaje)</li>
            </ul>
        </div>
    </div>

</div>