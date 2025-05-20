<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Empleado;

/* @var $this yii\web\View */
/* @var $model app\models\Mesa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mesa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_mesa')->textInput() ?>

    <?= $form->field($model, 'capacidad')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList([
        'Disponible' => 'Disponible',
        'Ocupada' => 'Ocupada',
        'Reservada' => 'Reservada',
        'Mantenimiento' => 'Mantenimiento',
    ], ['prompt' => 'Seleccionar estado...']) ?>

    <?= $form->field($model, 'id_empleado')->dropDownList(
        ArrayHelper::map(Empleado::find()->all(), 'id_empleado', function($empleado) {
            return $empleado->nombre . ' ' . $empleado->apellido;
        }),
        ['prompt' => 'Seleccionar empleado...']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>