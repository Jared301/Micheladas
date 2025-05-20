<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\Models\Proveedores $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="proveedores-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_empresa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'correo_electronico')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>