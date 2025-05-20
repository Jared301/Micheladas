<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Producto;

/* @var $this yii\web\View */
/* @var $model app\models\Promocion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promocion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'descuento')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <?= $form->field($model, 'fecha_inicio')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'fecha_fin')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'id_producto')->dropDownList(
        ArrayHelper::map(Producto::find()->all(), 'id_producto', 'nombre'),
        ['prompt' => 'Seleccionar producto...']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>