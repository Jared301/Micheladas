<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inventario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Agregar al Inventario', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_inventario',
            [
                'attribute' => 'id_ingrediente',
                'value' => function ($model) {
                    return $model->ingrediente ? $model->ingrediente->nombre : 'No asignado';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Ingrediente::find()->asArray()->all(), 'id_ingrediente', 'nombre'),
            ],
            [
                'attribute' => 'cantidad_actual',
                'format' => 'raw',
                'value' => function ($model) {
                    $unidad = $model->ingrediente ? $model->ingrediente->unidad_medida : '';
                    return $model->cantidad_actual . ' ' . $unidad;
                },
            ],
            [
                'attribute' => 'fecha_actualizacion',
                'format' => 'datetime',
                'filter' => Html::activeInput('date', $searchModel, 'fecha_actualizacion', ['class' => 'form-control']),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id' => $model->id_inventario];
                    }
                    if ($action === 'update') {
                        return ['update', 'id' => $model->id_inventario];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id' => $model->id_inventario];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>