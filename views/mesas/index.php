<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MesaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mesas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mesa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Mesa', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_mesa',
            'numero_mesa',
            'capacidad',
            'estado',
            [
                'attribute' => 'id_empleado',
                'value' => function ($model) {
                    return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'Sin asignar';
                },
                'filter' => Html::activeTextInput($searchModel, 'nombre_empleado', ['class' => 'form-control']),
                'label' => 'Empleado'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id' => $model->id_mesa];
                    }
                    if ($action === 'update') {
                        return ['update', 'id' => $model->id_mesa];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id' => $model->id_mesa];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>