<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Proveedores;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IngredienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingredientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingrediente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Ingrediente', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_ingrediente',
            'nombre',
            'tipo',
            'unidad_medida',
            [
                'attribute' => 'id_proveedor',
                'value' => function ($model) {
                    return $model->proveedor ? $model->proveedor->nombre_empresa : 'No asignado';
                },
                'filter' => ArrayHelper::map(Proveedores::find()->asArray()->all(), 'id_proveedor', 'nombre_empresa'),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id' => $model->id_ingrediente];
                    }
                    if ($action === 'update') {
                        return ['update', 'id' => $model->id_ingrediente];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id' => $model->id_ingrediente];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>