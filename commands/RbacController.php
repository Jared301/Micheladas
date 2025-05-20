<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Empleado;

/**
 * Controlador de consola para inicializar RBAC y crear usuarios de prueba.
 */
class RbacController extends Controller
{
    /**
     * Acción init: crea permisos y roles básicos.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // 1) Crear permisos
        $pCreateVenta = $auth->createPermission('createVenta');
        $pCreateVenta->description = 'Crear venta';
        $auth->add($pCreateVenta);

        $pManageProductos = $auth->createPermission('manageProductos');
        $pManageProductos->description = 'Administrar productos';
        $auth->add($pManageProductos);

        // 2) Crear roles y asignar permisos
        $rUsuario = $auth->createRole('usuario');
        $auth->add($rUsuario);
        $auth->addChild($rUsuario, $pCreateVenta);

        $rAdmin = $auth->createRole('admin');
        $auth->add($rAdmin);
        $auth->addChild($rAdmin, $rUsuario);
        $auth->addChild($rAdmin, $pManageProductos);

        echo "✅ RBAC inicializado: roles 'usuario' y 'admin' creados con sus permisos.\n";
    }

    /**
     * Crea usuarios de prueba: 'admin' y 'pepe'.
     */
    public function actionCreateTestUsers()
    {
        $auth = Yii::$app->authManager;

        // —— Usuario Admin ——
        $admin = new Empleado();
        $admin->nombre            = 'Admin';
        $admin->apellido          = 'Principal';
        $admin->correo_electronico= 'admin@micheladas.local';
        $admin->username          = 'admin';
        $admin->password_hash     = Yii::$app->security->generatePasswordHash('Admin123!');
        $admin->auth_key          = Yii::$app->security->generateRandomString();
        $admin->role              = 'admin';
        if($admin->save()) {
            $auth->assign($auth->getRole('admin'), $admin->getId());
            echo " Usuario admin creado ['admin' / 'Admin123!']\n";
        } else {
            echo " Error al crear admin: ", implode(", ", $admin->getFirstErrors()), "\n";
        }

        // —— Usuario Pepe ——
        $user = new Empleado();
        $user->nombre            = 'Pepe';
        $user->apellido          = 'Cliente';
        $user->correo_electronico= 'pepe@micheladas.local';
        $user->username          = 'pepe';
        $user->password_hash     = Yii::$app->security->generatePasswordHash('Pepe123!');
        $user->auth_key          = Yii::$app->security->generateRandomString();
        $user->role              = 'usuario';
        if($user->save()) {
            $auth->assign($auth->getRole('usuario'), $user->getId());
            echo " Usuario pepe creado ['pepe' / 'Pepe123!']\n";
        } else {
            echo " Error al crear pepe: ", implode(", ", $user->getFirstErrors()), "\n";
        }
    }
}
