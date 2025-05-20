<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Venta;
use app\models\Producto;

/* @var $this yii\web\View */
/* @var $model app\models\Cuenta */
/* @var $form yii\widgets\ActiveForm */

// Obtener ventas recientes
$ventasItems = ArrayHelper::map(
    Venta::find()->orderBy(['fecha' => SORT_DESC])->limit(100)->all(),
    'id_venta',
    function($model) {
        return 'Venta #' . $model->id_venta . ' (' . Yii::$app->formatter->asDatetime($model->fecha) . ')';
    }
);

// Obtener productos
$productosItems = ArrayHelper::map(
    Producto::find()->orderBy(['nombre' => SORT_ASC])->all(),
    'id_producto',
    'nombre'
);
?>

<div class="cuenta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_venta')->dropDownList(
        $ventasItems,
        ['prompt' => 'Seleccione una venta']
    ) ?>

    <?= $form->field($model, 'id_producto')->dropDownList(
        $productosItems,
        [
            'prompt' => 'Seleccione un producto',
            'class' => 'form-control',
            'id' => 'producto-select',
            'onchange' => '
                var precio = $(this).find("option:selected").data("precio");
                if (precio) {
                    $("#producto-precio").text("$" + precio.toFixed(2));
                } else {
                    $("#producto-precio").text("---");
                }
            '
        ]
    ) ?>

    <?php if (!$model->isNewRecord && $model->producto): ?>
    <div class="alert alert-info">
        <p><strong>Precio del producto:</strong> <?= Yii::$app->formatter->asCurrency($model->producto->precio) ?></p>
    </div>
    <?php else: ?>
    <div class="form-group">
        <label class="control-label">Precio del Producto</label>
        <p class="form-control-static" id="producto-precio">---</p>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Cargar precios de productos para JavaScript
$this->registerJs("
    // Precios de productos
    var productosPrecios = " . json_encode(ArrayHelper::map(Producto::find()->all(), 'id_producto', 'precio')) . ";
    
    // Inicializar el select con los datos de precio
    $('#producto-select option').each(function() {
        var productoId = $(this).val();
        if (productoId) {
            $(this).attr('data-precio', productosPrecios[productoId]);
        }
    });
    
    // Establecer precio inicial si hay un producto seleccionado
    var selectedProductId = $('#producto-select').val();
    if (selectedProductId) {
        var precio = productosPrecios[selectedProductId];
        if (precio) {
            $('#producto-precio').text('$' + precio.toFixed(2));
        }
    }
");
?>