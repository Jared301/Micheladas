<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Proveedores;

/* @var $this yii\web\View */
/* @var $model app\models\Ingrediente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingrediente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unidad_medida')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_proveedor')->dropDownList(
        ArrayHelper::map(Proveedores::find()->all(), 'id_proveedor', 'nombre_empresa'),
        ['prompt' => 'Seleccione un proveedor']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>