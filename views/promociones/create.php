<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\Models\Promociones $model */

$this->title = 'Create Promociones';
$this->params['breadcrumbs'][] = ['label' => 'Promociones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promociones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
