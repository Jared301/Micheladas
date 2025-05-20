<?php
use dmstr\web\AdminLteAsset;
use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AdminLteAsset::register($this);
AppAsset::register($this);

$isGuest = Yii::$app->user->isGuest;
$role    = $isGuest ? 'guest' : Yii::$app->user->identity->role;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini <?= $isGuest ? 'guest' : $role ?>">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::to(['/site/index']) ?>" class="logo">
            <span class="logo-mini"><b>M</b></span>
            <span class="logo-lg"><b>Micheladas</b></span>
        </a>
        <!-- Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <?php if (!$isGuest): ?>
                <a href="#" class="sidebar-toggle" data-widget="pushmenu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            <?php endif; ?>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if ($isGuest): ?>
                        <li><?= Html::a('Login', ['/site/login'], ['class'=>'nav-link']) ?></li>
                    <?php else: ?>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs"><?= Html::encode(Yii::$app->user->identity->username) ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-footer">
                                    <?= Html::beginForm(['/site/logout'], 'post') ?>
                                    <?= Html::submitButton('Cerrar sesión', ['class'=>'btn btn-default btn-flat']) ?>
                                    <?= Html::endForm() ?>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <?php if (!$isGuest): ?>
    <!-- Sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">
            <?= dmstr\widgets\Menu::widget([
                'options' => ['class'=>'sidebar-menu','data-widget'=>'tree'],
                'items' => [
                    ['label'=>'Inicio','icon'=>'dashboard','url'=>['/site/index']],
                    ['label'=>'Clientes','icon'=>'users','url'=>['/clientes/index']],
                    ['label'=>'Productos','icon'=>'mug-hot','url'=>['/producto/index']],
                    ['label'=>'Ventas','icon'=>'ticket-alt','url'=>['/ventas/index']],
                    ['label'=>'Ingredientes','icon'=>'lemon','url'=>['/ingredientes/index']],
                    ['label'=>'Inventario','icon'=>'warehouse','url'=>['/inventario/index']],
                    ['label'=>'Mesas','icon'=>'table','url'=>['/mesas/index']],
                    ['label'=>'Métodos de Pago','icon'=>'credit-card','url'=>['/metodospago/index']],
                    ['label'=>'Promociones','icon'=>'tags','url'=>['/promociones/index']],
                    ['label'=>'Proveedores','icon'=>'truck','url'=>['/proveedores/index']],
                    ['label'=>'Dashboard','icon'=>'tachometer-alt','url'=>['/site/dashboard'],'visible'=>$role==='admin'],
                    ['label'=>'Empleados','icon'=>'user','url'=>['/empleados/index'],'visible'=>$role==='admin'],
                ],
            ]) ?>
        </section>
    </aside>
    <?php endif; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
        </section>
        <section class="content">
            <?= $content ?>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs"></div>
        <strong>&copy; <?= date('Y') ?> Micheladas.</strong> Todos los derechos reservados.
    </footer>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>```
