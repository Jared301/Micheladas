<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Producto;
use app\models\Ingrediente;

/* @var $this yii\web\View */
/* @var $model app\models\ProductoIngrediente */
/* @var $form yii\widgets\ActiveForm */

// Para obtener la lista de productos
$productosItems = ArrayHelper::map(
    Producto::find()->orderBy(['nombre' => SORT_ASC])->all(),
    'id_producto',
    'nombre'
);

// Para obtener la lista de ingredientes
$ingredientesItems = ArrayHelper::map(
    Ingrediente::find()->orderBy(['nombre' => SORT_ASC])->all(),
    'id_ingrediente',
    function($model) {
        return $model->nombre . ' (' . $model->unidad_medida . ')';
    }
);

// Determinar si es una actualización
$isUpdate = !$model->isNewRecord;
?>

<div class="producto-ingrediente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_producto')->dropDownList(
        $productosItems,
        [
            'prompt' => 'Seleccione un producto',
            'disabled' => $isUpdate, // Deshabilitar si es una actualización
        ]
    )->hint($isUpdate ? 'No se puede cambiar el producto en una relación existente' : '') ?>

    <?= $form->field($model, 'id_ingrediente')->dropDownList(
        $ingredientesItems,
        [
            'prompt' => 'Seleccione un ingrediente',
            'disabled' => $isUpdate, // Deshabilitar si es una actualización
            'onchange' => 'actualizarUnidadMedida(this.value)'
        ]
    )->hint($isUpdate ? 'No se puede cambiar el ingrediente en una relación existente' : '') ?>
    
    <?php if ($isUpdate): ?>
    <!-- Campos ocultos para mantener los valores originales -->
    <?= Html::activeHiddenInput($model, 'id_producto') ?>
    <?= Html::activeHiddenInput($model, 'id_ingrediente') ?>
    <?php endif; ?>

    <?= $form->field($model, 'cantidad')->textInput(['type' => 'number', 'step' => '0.01', 'min' => '0']) ?>
    
    <div class="form-group">
        <label class="control-label">Unidad de Medida</label>
        <p class="form-control-static" id="unidad-medida-display">
            <?php if ($model->ingrediente): ?>
                <?= $model->ingrediente->unidad_medida ?>
            <?php else: ?>
                Seleccione un ingrediente
            <?php endif; ?>
        </p>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$unidadesMedida = json_encode(ArrayHelper::map(
    Ingrediente::find()->all(),
    'id_ingrediente',
    'unidad_medida'
));

$script = <<<JS
// Unidades de medida de los ingredientes
var unidadesMedida = {$unidadesMedida};

// Función para actualizar la unidad de medida mostrada
function actualizarUnidadMedida(ingredienteId) {
    var unidad = unidadesMedida[ingredienteId] || 'N/A';
    $('#unidad-medida-display').text(unidad);
}
JS;

$this->registerJs($script);
?>