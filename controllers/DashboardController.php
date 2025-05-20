<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'clientesEmailData' => $this->getClientesEmailData(),
            'productoStockData' => $this->getProductoStockData(),
            'empleadosSalarioData'=> $this->getEmpleadosSalarioData(),
            'proveedoresData' => $this->getProveedoresData(),
            'metodosPagoData' => $this->getMetodosPagoData(),
            'ventasPorDiaData' => $this->getVentasPorDiaData(),
            'ingredientesPorTipoData' => $this->getIngredientesPorTipoData(),
            'inventarioData' => $this->getInventarioData(),
            'mesasEstadoData' => $this->getMesasEstadoData(),
            'promocionesData' => $this->getPromocionesData(),

        ]);
    }
    
    private function getClientesEmailData()
    {
        $data = Yii::$app->db->createCommand("
            SELECT 
                SUBSTRING_INDEX(correo_electronico, '@', -1) as dominio,
                COUNT(*) as cantidad
            FROM clientes
            GROUP BY dominio
            ORDER BY cantidad DESC
        ")->queryAll();
        
        // Si no hay datos, proporciona datos de ejemplo para pruebas
        if (empty($data)) {
            $data = [
                ['dominio' => 'example.com', 'cantidad' => 3],
                ['dominio' => 'gmail.com', 'cantidad' => 2]
            ];
        }
        
        return $data;
    }
        private function getProductoStockData()
    {
        return Yii::$app->db->createCommand("
            SELECT nombre, stock
            FROM producto
            ORDER BY stock DESC
        ")->queryAll();
    }
    private function getEmpleadosSalarioData()
    {
        return Yii::$app->db->createCommand("
            SELECT 
                CASE 
                    WHEN salario BETWEEN 1 AND 4999 THEN '1 - 4999'
                    WHEN salario BETWEEN 5000 AND 9999 THEN '5000 - 9999'
                    WHEN salario BETWEEN 10000 AND 14999 THEN '10000 - 14999'
                    WHEN salario BETWEEN 15000 AND 19999 THEN '15000 - 19999'
                    WHEN salario BETWEEN 20000 AND 24999 THEN '20000 - 24999'
                    WHEN salario >= 25000 THEN '25000 o más'
                    ELSE 'Sin información'
                END as rango,
                COUNT(*) as cantidad
            FROM empleados
            GROUP BY 
                CASE 
                    WHEN salario BETWEEN 1 AND 4999 THEN '1 - 4999'
                    WHEN salario BETWEEN 5000 AND 9999 THEN '5000 - 9999'
                    WHEN salario BETWEEN 10000 AND 14999 THEN '10000 - 14999'
                    WHEN salario BETWEEN 15000 AND 19999 THEN '15000 - 19999'
                    WHEN salario BETWEEN 20000 AND 24999 THEN '20000 - 24999'
                    WHEN salario >= 25000 THEN '25000 o más'
                    ELSE 'Sin información'
                END
            ORDER BY MIN(salario)
        ")->queryAll();
    }
    private function getProveedoresData()
    {
        return Yii::$app->db->createCommand("
            SELECT p.nombre_empresa, COUNT(i.id_ingrediente) as cantidad_ingredientes
            FROM proveedores p
            LEFT JOIN ingredientes i ON p.id_proveedor = i.id_proveedor
            GROUP BY p.id_proveedor
            ORDER BY cantidad_ingredientes DESC
        ")->queryAll();
    }
    private function getMetodosPagoData()
    {
        return Yii::$app->db->createCommand("
            SELECT mp.tipo as metodo_pago, COUNT(v.id_venta) as cantidad
            FROM metodos_de_pago mp
            LEFT JOIN ventas v ON mp.id_metodo_pago = v.id_metodo_pago
            GROUP BY mp.id_metodo_pago
            ORDER BY cantidad DESC
        ")->queryAll();
    }
    private function getVentasPorDiaData()
    {
        // Datos estáticos basados en la tabla que compartiste
        return [
            ['dia' => '2023-10-15', 'total_ventas' => 350.50, 'num_transacciones' => 1],
            ['dia' => '2023-10-16', 'total_ventas' => 600.25, 'num_transacciones' => 2], 
            ['dia' => '2023-10-17', 'total_ventas' => 465.75, 'num_transacciones' => 2]  
        ];
    }
    private function getIngredientesPorTipoData()
    {
        return Yii::$app->db->createCommand("
            SELECT 
                tipo,
                COUNT(*) as cantidad
            FROM ingredientes
            GROUP BY tipo
            ORDER BY cantidad DESC
        ")->queryAll();
    }
    private function getInventarioData()
    {
        return Yii::$app->db->createCommand("
            SELECT 
                i.nombre as ingrediente,
                inv.cantidad_actual
            FROM inventario inv
            JOIN ingredientes i ON inv.id_ingrediente = i.id_ingrediente
            ORDER BY inv.cantidad_actual DESC
        ")->queryAll();
    }
    private function getMesasEstadoData()
    {
        return Yii::$app->db->createCommand("
            SELECT 
                estado,
                COUNT(*) as cantidad
            FROM mesas
            GROUP BY estado
            ORDER BY cantidad DESC
        ")->queryAll();
    }
    private function getPromocionesData()
    {
        return Yii::$app->db->createCommand("
            SELECT 
                nombre,
                descuento
            FROM promociones
            ORDER BY descuento DESC
        ")->queryAll();
    }

}