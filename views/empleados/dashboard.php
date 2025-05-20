<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->title = 'Dashboard de Empleados';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="empleado-dashboard">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?= $totalEmpleados ?></h4>
                    <p>Total de Empleados</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfica de empleados por puesto -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Distribución por Puesto</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaPuestos" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de rangos salariales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Distribución por Rango Salarial</h3>
                </div>
                <div class="card-body">
                    <canvas id="graficaSalarios" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Gráfica de ventas por empleado -->
    <div class="card">
        <div class="card-header">
            <h3>Ventas por Empleado</h3>
        </div>
        <div class="card-body">
            <canvas id="graficaVentasEmpleado" width="400" height="200"></canvas>
        </div>
    </div>

    <br>

    <!-- Tabla de Empleados -->
    <div class="card">
        <div class="card-header">
            <h3>Listado de Empleados</h3>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $empleados,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id_empleado',
                    'nombre',
                    'apellido',
                    'puesto',
                    'telefono',
                    'correo_electronico',
                    [
                        'attribute' => 'salario',
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

</div>

<!-- Incluir Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JavaScript para crear las gráficas -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de empleados por puesto
    const ctxPuestos = document.getElementById('graficaPuestos').getContext('2d');
    const chartPuestos = new Chart(ctxPuestos, {
        type: 'pie',
        data: {
            labels: <?= $puestos ?>,
            datasets: [{
                label: 'Empleados por Puesto',
                data: <?= $cantidadPorPuesto ?>,
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
                    position: 'right',
                }
            }
        }
    });
    
    // Gráfica de rangos salariales
    const ctxSalarios = document.getElementById('graficaSalarios').getContext('2d');
    const chartSalarios = new Chart(ctxSalarios, {
        type: 'bar',
        data: {
            labels: <?= $rangosSalariales ?>,
            datasets: [{
                label: 'Cantidad de Empleados',
                data: <?= $cantidadPorRango ?>,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
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
    
    // Gráfica de ventas por empleado
    const ctxVentasEmpleado = document.getElementById('graficaVentasEmpleado').getContext('2d');
    const chartVentasEmpleado = new Chart(ctxVentasEmpleado, {
        type: 'bar',
        data: {
            labels: <?= $nombresEmpleados ?>,
            datasets: [{
                label: 'Número de Ventas',
                data: <?= $ventasEmpleados ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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
});
</script>