<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Ingrediente;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
/* @var $form yii\widgets\ActiveForm */

// Si id_ingrediente viene preestablecido en la URL
$preselectedIngrediente = Yii::$app->request->get('id_ingrediente');
if ($preselectedIngrediente && !$model->isNewRecord) {
    $model->id_ingrediente = $preselectedIngrediente;
}
?>

<div class="inventario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_ingrediente')->dropDownList(
        ArrayHelper::map(Ingrediente::find()->all(), 'id_ingrediente', 'nombre'),
        [
            'prompt' => 'Seleccione un ingrediente',
            'disabled' => !$model->isNewRecord, // Si no es nuevo registro, no permitir cambiar el ingrediente
        ]
    )->hint('Una vez creado, el ingrediente no puede cambiarse') ?>

    <?= $form->field($model, 'cantidad_actual')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Si ya existe el registro, mostrar historial de cambios
if (!$model->isNewRecord && $model->ingrediente): ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Información del Ingrediente</h3>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Nombre:</dt>
            <dd><?= $model->ingrediente->nombre ?></dd>
            
            <dt>Tipo:</dt>
            <dd><?= $model->ingrediente->tipo ?></dd>
            
            <dt>Unidad de Medida:</dt>
            <dd><?= $model->ingrediente->unidad_medida ?></dd>
            
            <dt>Última Actualización:</dt>
            <dd><?= Yii::$app->formatter->asDatetime($model->fecha_actualizacion) ?></dd>
        </dl>
    </div>
</div>
<?php endif; ?>