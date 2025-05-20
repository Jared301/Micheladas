<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->title = 'Dashboard de Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cliente-dashboard">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?= $totalClientes ?></h4>
                    <p>Total de Clientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfica de distribución por primera letra del apellido -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Distribución por Inicial del Apellido</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaLetras" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de dominios de correo -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Dominios de Correo Electrónico</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaDominios" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Tabla de Clientes -->
    <div class="card">
        <div class="card-header">
            <h3>Listado de Clientes</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $clientes,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_cliente',
                    'nombre',
                    'apellido',
                    'telefono',
                    'correo_electronico',
                    'direccion',
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
    // Gráfica de distribución por letra
    const ctxLetras = document.getElementById('graficaLetras').getContext('2d');
    const chartLetras = new Chart(ctxLetras, {
        type: 'bar',
        data: {
            labels: <?= $letras ?>,
            datasets: [{
                label: 'Clientes por Inicial del Apellido',
                data: <?= $cantidadesLetra ?>,
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
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Gráfica de dominios de correo
    const ctxDominios = document.getElementById('graficaDominios').getContext('2d');
    const chartDominios = new Chart(ctxDominios, {
        type: 'pie',
        data: {
            labels: <?= $dominios ?>,
            datasets: [{
                data: <?= $cantidadesDominio ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>