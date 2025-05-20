<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductoIngredienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingredientes por Producto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producto-ingrediente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Relación', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_producto',
                'value' => function ($model) {
                    return $model->producto ? $model->producto->nombre : 'No disponible';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Producto::find()->asArray()->all(), 'id_producto', 'nombre'),
            ],
            [
                'attribute' => 'id_ingrediente',
                'value' => function ($model) {
                    return $model->ingrediente ? $model->ingrediente->nombre : 'No disponible';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Ingrediente::find()->asArray()->all(), 'id_ingrediente', 'nombre'),
            ],
            [
                'attribute' => 'cantidad',
                'format' => 'raw',
                'value' => function ($model) {
                    $unidad = $model->ingrediente ? $model->ingrediente->unidad_medida : '';
                    return $model->cantidad . ' ' . $unidad;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente];
                    }
                    if ($action === 'update') {
                        return ['update', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>