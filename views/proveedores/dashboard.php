<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->title = 'Dashboard de Proveedores';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="proveedor-dashboard">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?= $totalProveedores ?></h4>
                    <p>Total de Proveedores</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfica de dominios de correo -->
        <div class="col-md-6">
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

    <!-- Lista de Proveedores -->
    <div class="card">
        <div class="card-header">
            <h3>Proveedores Registrados</h3>
        </div>
        <div class="card-body">
            <canvas id="graficaProveedores" width="800" height="400"></canvas>
        </div>
    </div>

    <br>

    <!-- Tabla de Proveedores -->
    <div class="card">
        <div class="card-header">
            <h3>Listado de Proveedores</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $proveedores,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_proveedor',
                    'nombre_empresa',
                    'contacto',
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
    // Gráfica de dominios de correo
    const ctxDominios = document.getElementById('graficaDominios').getContext('2d');
    const chartDominios = new Chart(ctxDominios, {
        type: 'pie',
        data: {
            labels: <?= $dominios ?>,
            datasets: [{
                data: <?= $cantidadPorDominio ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
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
    
    // Gráfica de proveedores (barras verticales)
    const ctxProveedores = document.getElementById('graficaProveedores').getContext('2d');
    const chartProveedores = new Chart(ctxProveedores, {
        type: 'bar',
        data: {
            labels: <?= $nombresEmpresas ?>,
            datasets: [{
                label: 'Proveedores Registrados',
                data: [1, 1, 1, 1, 1], // Un valor por cada proveedor
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
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
                    max: 2,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>