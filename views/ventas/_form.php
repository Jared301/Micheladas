<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $model app\models\Venta */
/* @var $productos app\models\Producto[] */
/* @var $empleados app\models\Empleado[] */
/* @var $detalles app\models\DetalleVenta[] */

$detalleInit = isset($detalles) && count($detalles)
    ? $detalles
    : [new \app\models\DetalleVenta()];
?>

<div class="venta-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'id_cliente')->dropDownList(
        ArrayHelper::map(\app\models\Cliente::find()->all(),'id_cliente','nombre'),
        ['prompt'=>'-- Selecciona Cliente --']
    ) ?>

    <?= $form->field($model,'id_empleado')->dropDownList(
        ArrayHelper::map($empleados,'id_empleado',function($e){
            return $e->nombre . ' ' . $e->apellido;
        }),
        ['prompt'=>'-- Selecciona Empleado --']
    ) ?>

    <table class="table">
        <thead><tr><th>Producto</th><th>Cantidad</th><th></th></tr></thead>
        <tbody id="detalle-body">
            <?php foreach ($detalleInit as $i => $det): ?>
            <tr>
                <td>
                    <?= Html::dropDownList(
                        'productos[]',
                        $det->id_producto ?? null,
                        ArrayHelper::map($productos,'id_producto','nombre'),
                        ['class'=>'form-control','prompt'=>'--']
                    ) ?>
                </td>
                <td>
                    <?= Html::input(
                        'number',
                        'cantidades[]',
                        $det->cantidad ?? 1,
                        ['class'=>'form-control','min'=>1]
                    ) ?>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-line">–</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="button" id="add-line" class="btn btn-success mb-3">Agregar producto</button>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Registrar Venta' : 'Actualizar Venta',
            ['class'=>'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs(<<<'JS'
$('#add-line').on('click', function(){
    var row = $('#detalle-body tr:first').clone();
    row.find('select').val('');
    row.find('input').val(1);
    $('#detalle-body').append(row);
});
$(document).on('click', '.remove-line', function(){
    if ($('#detalle-body tr').length>1) {
        $(this).closest('tr').remove();
    }
});
JS
);
?>
