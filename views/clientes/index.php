<?php

use app\Models\Cliente;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cliente', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Exportar a PDF', ['export-pdf'], [
            'class' => 'btn btn-primary',
            'target' => '_blank',
            'data-toggle' => 'tooltip',
            'title' => 'Exportar tabla a PDF'
        ]) ?>
    </p>
    
    <?=Html::beginForm(['clientes/importar'], 'post',['enctype' => 'multipart/form-data']) ?>
        <?=Html:: fileInput('archivo_csv') ?>
        <?=Html:: submitButton('Importar CSV', ['class','btn btn-success' ]) ?>
    <?=Html::endForm()?> 
    
    <?php Pjax::begin(); ?>

    <div class="cliente-search">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['data-pjax' => true]
        ]); ?>

        <?= $form->field($searchModel, 'nombre')->textInput(['placeholder' => 'Buscar cliente...'])->label(false) ?>

        <?php ActiveForm::end(); ?>
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id_cliente',
            'nombre',
            'apellido',
            'telefono',
            'correo_electronico',
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['view', 'id_cliente' => $model->id_cliente]);
                    }
                    if ($action === 'update') {
                        return Url::to(['update', 'id_cliente' => $model->id_cliente]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['delete', 'id_cliente' => $model->id_cliente]);
                    }
                },
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>