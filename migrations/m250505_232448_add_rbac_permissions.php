<?php

use yii\db\Migration;

class m250505_232448_add_rbac_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;

        // Permiso para ver Dashboard
        $viewDashboard = $auth->createPermission('viewDashboard');
        $viewDashboard->description = 'Ver sección Dashboard';
        $auth->add($viewDashboard);

        // Permiso para administrar Empleados
        $manageEmpleados = $auth->createPermission('manageEmpleados');
        $manageEmpleados->description = 'Administrar Empleados';
        $auth->add($manageEmpleados);

        // Asignar permisos al rol admin
        $adminRole = $auth->getRole('admin');
        if ($adminRole) {
            $auth->addChild($adminRole, $viewDashboard);
            $auth->addChild($adminRole, $manageEmpleados);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;

        foreach (['viewDashboard', 'manageEmpleados'] as $name) {
            if ($perm = $auth->getPermission($name)) {
                $auth->remove($perm);
            }
        }
    }
}
