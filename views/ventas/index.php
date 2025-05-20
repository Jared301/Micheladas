<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VentaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ventas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Venta', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Importar CSV', ['import'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php Pjax::begin(); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_venta',
            [
                'attribute' => 'fecha',
                'format'    => 'datetime',
                'filter'    => Html::activeInput('date', $searchModel, 'fecha', ['class' => 'form-control']),
            ],
            [
                'attribute'      => 'total',
                'format'         => 'currency',
                'contentOptions' => ['style' => 'text-align: right;'],
            ],
            [
                'attribute' => 'id_cliente',
                'value'     => function ($model) {
                    return $model->cliente
                        ? $model->cliente->nombre . ' ' . $model->cliente->apellido
                        : 'No asignado';
                },
                'filter'    => \yii\helpers\ArrayHelper::map(
                    \app\models\Cliente::find()->asArray()->all(),
                    'id_cliente',
                    function($m){ return $m['nombre'].' '.$m['apellido']; }
                ),
            ],
            [
                'attribute' => 'id_empleado',
                'value'     => function ($model) {
                    return $model->empleado
                        ? $model->empleado->nombre . ' ' . $model->empleado->apellido
                        : 'No asignado';
                },
                'filter'    => \yii\helpers\ArrayHelper::map(
                    \app\models\Empleado::find()->asArray()->all(),
                    'id_empleado',
                    function($m){ return $m['nombre'].' '.$m['apellido']; }
                ),
            ],
            [
                'attribute' => 'id_metodo_pago',
                'value'     => function ($model) {
                    return $model->metodoPago
                        ? $model->metodoPago->tipo
                        : 'No asignado';
                },
                'filter'    => \yii\helpers\ArrayHelper::map(
                    \app\models\Metodospago::find()->asArray()->all(),
                    'id_metodo_pago',
                    'tipo'
                ),
            ],

            // Aquí la columna de acciones, incluyendo el botón Ticket
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {ticket}',
                'buttons'  => [
                    'ticket' => function ($url, $model, $key) {
                        return Html::a(
                            '🖨️',
                            ['ticket', 'id' => $model->id_venta],
                            [
                                'title'    => 'Imprimir Ticket',
                                'target'   => '_blank',
                                'data-pjax'=> '0',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
