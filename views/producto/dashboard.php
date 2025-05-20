<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->title = 'Dashboard de Productos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="producto-dashboard">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?= $totalProductos ?></h4>
                    <p>Total de Productos</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfica de precios por producto -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Precios por Producto</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaPrecios" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica circular de tamaños -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Distribución por Tamaño</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaTamaños" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Tabla de Productos -->
    <div class="card">
        <div class="card-header">
            <h3>Listado de Productos</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $productos,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_producto',
                    'nombre',
                    'descripcion:ntext',
                    [
                        'attribute' => 'precio',
                        'format' => ['currency'],
                    ],
                    'tamaño',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <br>

    <!-- Tabla de Productos con Promociones -->
    <?php if (!empty($productosConPromocion)): ?>
    <div class="card">
        <div class="card-header">
            <h3>Productos con Promociones</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $productosConPromocion,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_producto',
                    'nombre',
                    [
                        'attribute' => 'precio',
                        'format' => ['currency'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Incluir Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JavaScript para crear las gráficas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de precios
    const ctxPrecios = document.getElementById('graficaPrecios').getContext('2d');
    const chartPrecios = new Chart(ctxPrecios, {
        type: 'bar',
        data: {
            labels: <?= $nombresProductos ?>,
            datasets: [{
                label: 'Precio (MXN)',
                data: <?= $preciosProductos ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$ ' + value;
                        }
                    }
                }
            }
        }
    });
    
    // Gráfica de tamaños
    const ctxTamaños = document.getElementById('graficaTamaños').getContext('2d');
    const chartTamaños = new Chart(ctxTamaños, {
        type: 'pie',
        data: {
            labels: <?= $tamañosLabels ?>,
            datasets: [{
                label: 'Tamaños',
                data: <?= $tamañosValores ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
});
</script>

?>