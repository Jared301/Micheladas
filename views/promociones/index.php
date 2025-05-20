<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PromocionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promociones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promocion-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Promoción', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_promocion',
            'nombre',
            'descripcion:ntext',
            [
                'attribute' => 'descuento',
                'value' => function ($model) {
                    return $model->descuento . '%';
                },
            ],
            'fecha_inicio:date',
            'fecha_fin:date',
            [
                'attribute' => 'id_producto',
                'value' => function ($model) {
                    return $model->producto ? $model->producto->nombre : 'Sin asignar';
                },
                'filter' => Html::activeTextInput($searchModel, 'nombre_producto', ['class' => 'form-control']),
                'label' => 'Producto'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id_promocion' => $model->id_promocion];
                    }
                    if ($action === 'update') {
                        return ['update', 'id_promocion' => $model->id_promocion];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id_promocion' => $model->id_promocion];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>