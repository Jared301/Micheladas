<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->title = 'Dashboard de Métodos de Pago';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="metodo-pago-dashboard">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?= $totalMetodos ?></h4>
                    <p>Total de Métodos de Pago</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfica de uso de métodos de pago -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Uso de Métodos de Pago</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaUsoMetodos" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución de tipos de métodos -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Tipos de Métodos de Pago</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaTiposMetodo" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Tabla de Métodos de Pago -->
    <div class="card">
        <div class="card-header">
            <h3>Listado de Métodos de Pago</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $metodosPago,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_metodo_pago',
                    'tipo',
                    'detalles',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>



<!-- Incluir Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JavaScript para crear las gráficas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de uso de métodos de pago
    const ctxUsoMetodos = document.getElementById('graficaUsoMetodos').getContext('2d');
    const chartUsoMetodos = new Chart(ctxUsoMetodos, {
        type: 'pie',
        data: {
            labels: <?= $tiposMetodos ?>,
            datasets: [{
                data: <?= $ventasPorMetodo ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(201, 203, 207, 0.6)',
                    'rgba(255, 99, 255, 0.6)',
                    'rgba(99, 255, 132, 0.6)',
                    'rgba(99, 132, 255, 0.6)'
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
    
    // Gráfica de tipos de métodos de pago
    const ctxTiposMetodo = document.getElementById('graficaTiposMetodo').getContext('2d');
    const chartTiposMetodo = new Chart(ctxTiposMetodo, {
        type: 'bar',
        data: {
            labels: ['Digital', 'Efectivo', 'Financiamiento', 'Otros'],
            datasets: [{
                label: 'Porcentaje de métodos de pago',
                data: [60, 20, 15, 5], // Porcentajes aproximados
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>