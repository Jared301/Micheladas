<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cuenta */

$this->title = 'Crear Cuenta';
$this->params['breadcrumbs'][] = ['label' => 'Cuentas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuenta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>