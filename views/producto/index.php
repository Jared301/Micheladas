<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Producto', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_producto',
            'nombre',
            'descripcion:ntext',
            [
                'attribute' => 'precio',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align: right;'],
            ],
            'stock',
            'tamaño',
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id_producto' => $model->id_producto];
                    }
                    if ($action === 'update') {
                        return ['update', 'id_producto' => $model->id_producto];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id_producto' => $model->id_producto];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>