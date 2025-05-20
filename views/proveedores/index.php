<?php

use app\Models\Proveedor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proveedor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Proveedor', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Exportar a PDF', ['export-pdf'], [
            'class' => 'btn btn-primary',
            'target' => '_blank',
            'data-toggle' => 'tooltip',
            'title' => 'Exportar tabla a PDF'
        ]) ?>
    </p>
    
    <?=Html::beginForm(['proveedores/importar'], 'post',['enctype' => 'multipart/form-data']) ?>
        <?=Html:: fileInput('archivo_csv') ?>
        <?=Html:: submitButton('Importar CSV', ['class','btn btn-success' ]) ?>
    <?=Html::endForm()?> 
    
    <?php Pjax::begin(); ?>

    <div class="proveedor-search">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['data-pjax' => true]
        ]); ?>

        <?= $form->field($searchModel, 'nombre_empresa')->textInput(['placeholder' => 'Buscar proveedor...'])->label(false) ?>

        <?php ActiveForm::end(); ?>
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id_proveedor',
            'nombre_empresa',
            'contacto',
            'telefono',
            'correo_electronico',
            'direccion',
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::to(['view', 'id_proveedor' => $model->id_proveedor]);
                    }
                    if ($action === 'update') {
                        return Url::to(['update', 'id_proveedor' => $model->id_proveedor]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['delete', 'id_proveedor' => $model->id_proveedor]);
                    }
                },
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>