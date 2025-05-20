<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CuentaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cuentas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuenta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Cuenta', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Exportar PDF', ['export-pdf'], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_cuenta',
            [
                'attribute' => 'id_venta',
                'value' => function ($model) {
                    return $model->venta ? 'Venta #' . $model->id_venta . ' (' . Yii::$app->formatter->asDate($model->venta->fecha) . ')' : 'No disponible';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Venta::find()->orderBy(['id_venta' => SORT_DESC])->limit(100)->asArray()->all(), 'id_venta', function($model) {
                    return 'Venta #' . $model['id_venta'] . ' (' . Yii::$app->formatter->asDate($model['fecha']) . ')';
                }),
            ],
            [
                'attribute' => 'id_producto',
                'value' => function ($model) {
                    return $model->producto ? $model->producto->nombre : 'No disponible';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Producto::find()->orderBy(['nombre' => SORT_ASC])->asArray()->all(), 'id_producto', 'nombre'),
            ],
            [
                'attribute' => 'venta_fecha',
                'value' => function ($model) {
                    return $model->venta ? Yii::$app->formatter->asDatetime($model->venta->fecha) : 'No disponible';
                },
                'filter' => Html::activeInput('date', $searchModel, 'venta_fecha', ['class' => 'form-control']),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return ['view', 'id' => $model->id_cuenta];
                    }
                    if ($action === 'update') {
                        return ['update', 'id' => $model->id_cuenta];
                    }
                    if ($action === 'delete') {
                        return ['delete', 'id' => $model->id_cuenta];
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>