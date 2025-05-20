<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * Crea permisos y roles iniciales
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // 1) Permisos
        $pCreateVenta     = $auth->createPermission('createVenta');
        $pCreateVenta->description = 'Crear venta';
        $auth->add($pCreateVenta);

        $pManageProductos = $auth->createPermission('manageProductos');
        $pManageProductos->description = 'Administrar productos';
        $auth->add($pManageProductos);

        // 2) Roles
        $rUsuario = $auth->createRole('usuario');
        $auth->add($rUsuario);
        $auth->addChild($rUsuario, $pCreateVenta);

        $rAdmin = $auth->createRole('admin');
        $auth->add($rAdmin);
        $auth->addChild($rAdmin, $rUsuario);
        $auth->addChild($rAdmin, $pManageProductos);

        echo "✅ RBAC inicializado: permisos y roles creados.\n";
    }
}
