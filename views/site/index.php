<?php
use yii\helpers\Html;
use yii\helpers\Url;
// Usar la clase Carousel de Bootstrap 3 en lugar de Bootstrap 5
use yii\bootstrap\Carousel;

$this->title = 'Mi Aplicación';
?>

<!-- Estilos CSS para el menú de hamburguesa y carrusel reducido -->
<style>
    /* Estilo para el menú de hamburguesa */
    .hamburger-menu {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 30px;
        height: 20px;
    }
    .hamburger-menu span {
        display: block;
        height: 3px;
        width: 100%;
        background-color: #333;
        transition: all 0.3s;
    }
    /* Menú lateral */
    .side-menu {
        position: fixed;
        left: -250px;
        top: 0;
        width: 250px;
        height: 100%;
        background-color: #333;
        z-index: 999;
        transition: left 0.3s;
        padding-top: 60px;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0,0,0,0.2);
    }
    .side-menu.active { left: 0; }
    .side-menu a {
        display: block;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid #444;
        transition: background-color 0.3s;
    }
    .side-menu a:hover { background-color: #444; }
    /* Overlay */
    .menu-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 998;
    }
    .menu-overlay.active { display: block; }
    /* Ajuste para carrusel reducido */
    .carousel-container {
        max-width: 800px;
        margin: 20px auto;
    }
    .carousel-item img {
        height: 800px;
        object-fit: cover;
        width: 100%;
    }
    /* Encabezado de la app */
    .app-header {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-left: 50px;
        margin-bottom: 20px;
    }
    .app-title {
        text-align: center;
        flex-grow: 1;
    }
</style>

<!-- Menú de hamburguesa -->
<div class="hamburger-menu" id="hamburger-btn">
    <span></span><span></span><span></span>
</div>

<!-- Menú lateral -->
<div class="side-menu" id="side-menu">
    <a href="<?= Url::to(['/site/index']) ?>">Inicio</a>
    <a href="<?= Url::to(['/dashboard/index']) ?>">Dashboard</a>
    <a href="<?= Url::to(['/clientes/index']) ?>">Clientes</a>
    <a href="<?= Url::to(['/producto/index']) ?>">Productos</a>
    <a href="<?= Url::to(['/ventas/index']) ?>">Ventas</a>
    <a href="<?= Url::to(['/empleados/index']) ?>">Empleados</a>
    <a href="<?= Url::to(['/ingredientes/index']) ?>">Ingredientes</a>
    <a href="<?= Url::to(['/inventario/index']) ?>">Inventario</a>
    <a href="<?= Url::to(['/mesas/index']) ?>">Mesas</a>
    <a href="<?= Url::to(['/metodospago/index']) ?>">Métodos de Pago</a>
    <a href="<?= Url::to(['/promociones/index']) ?>">Promociones</a>
    <a href="<?= Url::to(['/proveedores/index']) ?>">Proveedores</a>
</div>

<!-- Overlay -->
<div class="menu-overlay" id="menu-overlay"></div>

<!-- Contenido principal -->
<div class="site-index">
    <div class="app-header">
        <div class="app-title">
            <h1>Micheladas</h1>
        </div>
    </div>

    <!-- Carrusel en contenedor reducido -->
    <div class="carousel-container">
        <?= Carousel::widget([
            'items' => [
                [
                    'content' => Html::img('@web/images/slide1.jpg', ['class'=>'d-block w-100']),
                    'caption' => '<h5>Slide 1</h5>'
                ],
                [
                    'content' => Html::img('@web/images/slide2.jpg', ['class'=>'d-block w-100']),
                    'caption' => '<h5>Slide 2</h5>'
                ],
                [
                    'content' => Html::img('@web/images/slide3.jpg', ['class'=>'d-block w-100']),
                    'caption' => '<h5>Slide 3</h5>'
                ],
            ],
            'clientOptions' => [
                'interval' => 3000,
                'wrap'     => true,
                'pause'    => 'hover',
            ],
        ]) ?>
    </div>

    <!-- Resto del contenido -->
    <div class="jumbotron text-center bg-transparent"></div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4"><h2></h2></div>
            <div class="col-lg-4"><h2></h2></div>
            <div class="col-lg-4"></div>
        </div>
    </div>
</div>

<!-- JavaScript menú hamburguesa -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn     = document.getElementById('hamburger-btn');
    const menu    = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    function toggle() {
        menu.classList.toggle('active');
        overlay.classList.toggle('active');
    }
    btn.addEventListener('click', toggle);
    overlay.addEventListener('click', toggle);
});
</script>
