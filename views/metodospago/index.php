<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MetodospagoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Métodos de Pago';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodospago-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Método de Pago', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_metodo_pago',
            'tipo',
            'detalles',

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id_metodo_pago' => $model->id_metodo_pago];
                    }
                    if ($action === 'update') {
                        return ['update', 'id_metodo_pago' => $model->id_metodo_pago];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id_metodo_pago' => $model->id_metodo_pago];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>