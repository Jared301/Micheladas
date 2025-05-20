<?php
/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\helpers\Html;
// Usa ActiveForm de Bootstrap 3 en lugar de bootstrap5
use yii\bootstrap\ActiveForm;

$this->title = 'Iniciar sesión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor ingresa tu usuario y contraseña:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                ],
            ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Iniciar sesión', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
