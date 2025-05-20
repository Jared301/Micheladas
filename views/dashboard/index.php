<?php
use yii\helpers\Html;
$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dashboard-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <!-- Título de la sección para la tabla Clientes -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 30px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Clientes</h2>
        </div>
    </div>
    
    <!-- Gráfica de Dominios de Correo Electrónico -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Distribución de dominios de correo electrónico</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="clientesEmailChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Título de la sección para la tabla Productos -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Productos</h2>
        </div>
    </div>
    
    <!-- Gráfica de Stock de Productos -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Stock de Productos</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="productoStockChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Título de la sección para la tabla Empleados -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Empleados</h2>
        </div>
    </div>
    
    <!-- Gráfica de Rangos de Salarios -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Distribución de salarios por rangos</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="empleadosSalarioChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
        <!-- Título de la sección para la tabla Proveedores -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Proveedores</h2>
        </div>
    </div>

    <!-- Gráfica de Ingredientes por Proveedor -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Número de ingredientes por proveedor</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="proveedoresChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Título de la sección para la tabla Métodos de Pago -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Métodos de Pago</h2>
        </div>
    </div>

    <!-- Gráfica de Métodos de Pago -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Distribución de métodos de pago utilizados</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="metodosPagoChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Título de la sección para la tabla Ventas -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Ventas</h2>
        </div>
    </div>

    <!-- Gráfica de Ventas por Día -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Total de ventas por día</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="ventasChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Título de la sección para la tabla Ingredientes -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Ingredientes</h2>
        </div>
    </div>

    <!-- Gráfica de Ingredientes por Tipo -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Distribución de ingredientes por tipo</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="ingredientesTipoChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Título de la sección para la tabla Inventario -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Inventario</h2>
        </div>
    </div>

    <!-- Gráfica de Inventario -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cantidad actual de ingredientes en inventario</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="inventarioChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Título de la sección para la tabla Mesas -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Mesas</h2>
        </div>
    </div>

    <!-- Gráfica de Estado de Mesas -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Distribución de mesas por estado</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="mesasEstadoChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Título de la sección para la tabla Promociones -->
    <div class="row">
        <div class="col-md-12">
            <h2 style="margin-top: 40px; margin-bottom: 20px; color: #337ab7;">Gráfica tabla Promociones</h2>
        </div>
    </div>

    <!-- Gráfica de Promociones -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Porcentaje de descuento por promoción</h3>
                </div>
                <div class="panel-body" style="min-height: 400px;">
                    <canvas id="promocionesChart" width="800" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cargar Chart.js directamente -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para gráfica de dominios de correo
    var emailLabels = <?= json_encode(array_column($clientesEmailData, 'dominio')) ?>;
    var emailValues = <?= json_encode(array_column($clientesEmailData, 'cantidad')) ?>;
    
    // Crear gráfica de dominios de correo
    var emailCanvas = document.getElementById('clientesEmailChart');
    if (emailCanvas) {
        var emailCtx = emailCanvas.getContext('2d');
        var emailChart = new Chart(emailCtx, {
            type: 'pie',
            data: {
                labels: emailLabels,
                datasets: [{
                    data: emailValues,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(201, 203, 207, 0.7)',
                        'rgba(0, 128, 0, 0.7)',
                        'rgba(139, 0, 0, 0.7)',
                        'rgba(0, 0, 139, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Datos para gráfica de stock de productos
    var productoLabels = <?= json_encode(array_column($productoStockData, 'nombre')) ?>;
    var productoValues = <?= json_encode(array_column($productoStockData, 'stock')) ?>;
    
    // Crear gráfica de stock de productos
    var stockCanvas = document.getElementById('productoStockChart');
    if (stockCanvas) {
        var stockCtx = stockCanvas.getContext('2d');
        var stockChart = new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: productoLabels,
                datasets: [{
                    label: 'Stock disponible',
                    data: productoValues,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad en stock'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Producto'
                        }
                    }
                }
            }
        });
    }
    
    // Datos para gráfica de rangos de salarios
    var salarioLabels = <?= json_encode(array_column($empleadosSalarioData, 'rango')) ?>;
    var salarioValues = <?= json_encode(array_column($empleadosSalarioData, 'cantidad')) ?>;
    
    // Crear gráfica de rangos de salarios
    var salarioCanvas = document.getElementById('empleadosSalarioChart');
    if (salarioCanvas) {
        var salarioCtx = salarioCanvas.getContext('2d');
        var salarioChart = new Chart(salarioCtx, {
            type: 'pie',
            data: {
                labels: salarioLabels,
                datasets: [{
                    data: salarioValues,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' empleados (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Datos para gráfica de proveedores
    var proveedoresLabels = <?= json_encode(array_column($proveedoresData, 'nombre_empresa')) ?>;
    var proveedoresValues = <?= json_encode(array_column($proveedoresData, 'cantidad_ingredientes')) ?>;

    // Crear gráfica de proveedores
    var proveedoresCanvas = document.getElementById('proveedoresChart');
    if (proveedoresCanvas) {
        var proveedoresCtx = proveedoresCanvas.getContext('2d');
        var proveedoresChart = new Chart(proveedoresCtx, {
            type: 'bar',
            data: {
                labels: proveedoresLabels,
                datasets: [{
                    label: 'Ingredientes suministrados',
                    data: proveedoresValues,
                    backgroundColor: 'rgba(60, 141, 188, 0.7)',
                    borderColor: 'rgba(60, 141, 188, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de ingredientes'
                        },
                        ticks: {
                            precision: 0 // Asegura que solo se muestren números enteros
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Proveedor'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + ' ingredientes';
                            }
                        }
                    }
                }
            }
        });
    }
    // Datos para gráfica de métodos de pago
    var metodosPagoLabels = <?= json_encode(array_column($metodosPagoData, 'metodo_pago')) ?>;
    var metodosPagoValues = <?= json_encode(array_column($metodosPagoData, 'cantidad')) ?>;

    // Crear gráfica de métodos de pago
    var metodosPagoCanvas = document.getElementById('metodosPagoChart');
    if (metodosPagoCanvas) {
        var metodosPagoCtx = metodosPagoCanvas.getContext('2d');
        var metodosPagoChart = new Chart(metodosPagoCtx, {
            type: 'pie',
            data: {
                labels: metodosPagoLabels,
                datasets: [{
                    data: metodosPagoValues,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',   // Azul
                        'rgba(255, 99, 132, 0.7)',   // Rojo
                        'rgba(255, 206, 86, 0.7)',   // Amarillo
                        'rgba(75, 192, 192, 0.7)',   // Verde azulado
                        'rgba(153, 102, 255, 0.7)',  // Púrpura
                        'rgba(255, 159, 64, 0.7)',   // Naranja
                        'rgba(201, 203, 207, 0.7)',  // Gris
                        'rgba(0, 128, 0, 0.7)',      // Verde
                        'rgba(139, 0, 0, 0.7)',      // Marrón
                        'rgba(0, 0, 139, 0.7)'       // Azul oscuro
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' ventas (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    // Datos para gráfica de ventas por día 

    var ventasDias = ['2023-10-15', '2023-10-16', '2023-10-17'];
    var ventasTotales = [350.50, 600.25, 465.75];

    // Crear gráfica de ventas simplificada
    var ventasCanvas = document.getElementById('ventasChart');
    if (ventasCanvas) {
        var ventasCtx = ventasCanvas.getContext('2d');
        var ventasChart = new Chart(ventasCtx, {
            type: 'bar',
            data: {
                labels: ventasDias,
                datasets: [{
                    label: 'Total de ventas ($)',
                    data: ventasTotales,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total de ventas ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha'
                        }
                    }
                }
            }
        });
    }
    // Datos estáticos para gráfica de ingredientes por tipo basados en la tabla compartida
    var tiposIngrediente = ['Condimento', 'Fruta', 'Utensilio', 'Cítrico', 'Endulzante', 'Bebida', 'Refrigerante', 'Decoración'];
    var cantidadesPorTipo = [5, 3, 2, 1, 1, 1, 1, 1];

    // Crear gráfica de ingredientes por tipo
    var ingredientesTipoCanvas = document.getElementById('ingredientesTipoChart');
    if (ingredientesTipoCanvas) {
        var ingredientesTipoCtx = ingredientesTipoCanvas.getContext('2d');
        var ingredientesTipoChart = new Chart(ingredientesTipoCtx, {
            type: 'pie',
            data: {
                labels: tiposIngrediente,
                datasets: [{
                    data: cantidadesPorTipo,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',   // Rojo - Condimento
                        'rgba(255, 206, 86, 0.7)',   // Amarillo - Fruta
                        'rgba(54, 162, 235, 0.7)',   // Azul - Utensilio
                        'rgba(75, 192, 192, 0.7)',   // Verde azulado - Cítrico
                        'rgba(153, 102, 255, 0.7)',  // Púrpura - Endulzante
                        'rgba(255, 159, 64, 0.7)',   // Naranja - Bebida
                        'rgba(201, 203, 207, 0.7)',  // Gris - Refrigerante
                        'rgba(0, 128, 0, 0.7)'       // Verde - Decoración
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' ingredientes (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    

    var ingredientesNombres = [
        'Azúcar', 'Hielo', 'Cerveza', 'Limón', 'Vaso', 
        'Chamoy', 'Sal', 'Tamarindo', 'Chile en polvo', 'Popote', 
        'Sombrilla', 'Mango', 'Piña', 'Sal de gusano', 'Tajín'
    ];

    var cantidadesInventario = [50, 50, 48, 45, 45, 35, 40, 30, 25, 40, 30, 20, 15, 20, 25];

    // Crear gráfica de inventario
    var inventarioCanvas = document.getElementById('inventarioChart');
    if (inventarioCanvas) {
        var inventarioCtx = inventarioCanvas.getContext('2d');
        var inventarioChart = new Chart(inventarioCtx, {
            type: 'bar',
            data: {
                labels: ingredientesNombres,
                datasets: [{
                    label: 'Cantidad en inventario',
                    data: cantidadesInventario,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad actual'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Ingrediente'
                        }
                    }
                }
            }
        });
    }
    // Datos estáticos para la gráfica de estado de mesas
    var estadosMesas = ['Disponible', 'Ocupada', 'Reservada', 'Mantenimiento'];
    var cantidadesPorEstado = [3, 2, 1, 1]; 

    // Crear gráfica de estado de mesas
    var mesasEstadoCanvas = document.getElementById('mesasEstadoChart');
    if (mesasEstadoCanvas) {
        var mesasEstadoCtx = mesasEstadoCanvas.getContext('2d');
        var mesasEstadoChart = new Chart(mesasEstadoCtx, {
            type: 'pie',
            data: {
                labels: estadosMesas,
                datasets: [{
                    data: cantidadesPorEstado,
                    backgroundColor: [
                        'rgba(0, 200, 0, 0.7)',    // Verde - Disponible
                        'rgba(255, 0, 0, 0.7)',     // Rojo - Ocupada
                        'rgba(255, 165, 0, 0.7)',   // Naranja - Reservada
                        'rgba(128, 128, 128, 0.7)'  // Gris - Mantenimiento
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' mesas (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    // Datos estáticos para la gráfica de promociones
    var promocionesNombres = [
        'Happy Hour Mango', '2x1 Tamarindo', 'Happy Hour Clásica', 
        'Promo Chamoy Picante', 'Promo Verano Mango', 
        'Promo Fin de Semana', 'Promo Piña Colada', 'Promo Azulito'
    ];

    var promocionesDescuentos = [50.00, 50.00, 30.00, 25.00, 20.00, 15.00, 15.00, 10.00];

    // Crear gráfica de promociones
    var promocionesCanvas = document.getElementById('promocionesChart');
    if (promocionesCanvas) {
        var promocionesCtx = promocionesCanvas.getContext('2d');
        var promocionesChart = new Chart(promocionesCtx, {
            type: 'bar',
            data: {
                labels: promocionesNombres,
                datasets: [{
                    label: 'Descuento (%)',
                    data: promocionesDescuentos,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(201, 203, 207, 0.7)',
                        'rgba(0, 128, 0, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                        'rgba(0, 128, 0, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Descuento (%)'
                        },
                        max: 60 // Para dar un poco de espacio en la parte superior del gráfico
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Promoción'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>