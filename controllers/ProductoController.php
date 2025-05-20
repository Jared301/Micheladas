<?php

namespace app\controllers;

use Yii;
use app\models\Producto;
use app\models\ProductoSearch;
use yii\data\ActiveDataProvider;
use app\models\Promocion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;


/**
 * ProductoController implements the CRUD actions for Producto model.
 */
class ProductoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Producto models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel'=> $searchModel,
            'dataProvider'=> $dataProvider
        ]);
    }

    /**
     * Displays a single Producto model.
     * @param int $id_producto ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_producto)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_producto),
        ]);
    }

    /**
     * Creates a new Producto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Producto();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_producto' => $model->id_producto]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Producto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_producto ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_producto)
    {
        $model = $this->findModel($id_producto);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_producto' => $model->id_producto]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Producto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_producto ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_producto)
    {
        $this->findModel($id_producto)->delete();

        return $this->redirect(['index']);
    }


    //----------------------------Para exportar a PDF ----------------------------------------------------
    

// Añadir estos métodos al ProductosController existente

/**
 * Exports data to a PDF file including related tables.
 * @return mixed
 */
    public function actionExportPdf()
    {
        // Si quieres exportar los productos filtrados
        $searchModel = new ProductoSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $productos = $dataProvider->models;
        } else {
            // Si no hay filtros, obtener todos los productos (sin cargar promociones)
            $productos = Producto::find()->all();
        }
        
        // Preparar contenido HTML para el PDF
        $content = $this->renderPartial('_pdf_view', [
            'productos' => $productos,
        ]);
        
        // Configurar el PDF
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE, // Horizontal para mostrar más columnas
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => '
                .kv-heading-1 { font-size:18px; text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 6px; border: 1px solid #ccc; }
                th { background-color: #f0f0f0; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            ',
            'options' => ['title' => 'Lista de Productos'],
            'methods' => [
                'SetHeader' => ['Lista de Productos - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        // Devolver el PDF al navegador
        return $pdf->render();
    }

/**
 * Imports productos data from a CSV file.
 * @return mixed
 */
public function actionImport()
    {
        $model = new \yii\base\DynamicModel([
            'csvFile' => null,
        ]);
        $model->addRule(['csvFile'], 'file', ['extensions' => 'csv']);

        if (Yii::$app->request->isPost) {
            $model->csvFile = UploadedFile::getInstance($model, 'csvFile');

            if ($model->validate()) {
                $filePath = $model->csvFile->tempName;
                
                // Verificar que el archivo se haya subido correctamente
                if (!file_exists($filePath)) {
                    Yii::$app->session->setFlash('error', 'Error al acceder al archivo CSV.');
                    return $this->redirect(['index']);
                }
                
                // Abrir el archivo
                $file = @fopen($filePath, 'r');
                if (!$file) {
                    Yii::$app->session->setFlash('error', 'No se pudo abrir el archivo CSV.');
                    return $this->redirect(['index']);
                }
                
                // Leer encabezados
                $headers = fgetcsv($file);
                if (!$headers) {
                    Yii::$app->session->setFlash('error', 'El archivo CSV está vacío o tiene un formato incorrecto.');
                    fclose($file);
                    return $this->redirect(['index']);
                }
                
                // Mapear índices de columnas (convertir todo a minúsculas para evitar problemas)
                $columnMap = [];
                foreach ($headers as $index => $header) {
                    $header = trim(mb_strtolower($header, 'UTF-8'));
                    $columnMap[$header] = $index;
                }
                
                // Verificar si los encabezados requeridos están presentes
                $requiredFields = ['nombre', 'descripcion', 'precio'];
                $missingFields = [];
                foreach ($requiredFields as $field) {
                    if (!isset($columnMap[$field])) {
                        $missingFields[] = $field;
                    }
                }
                
                if (!empty($missingFields)) {
                    Yii::$app->session->setFlash('error', 'Faltan campos obligatorios en el CSV: ' . implode(', ', $missingFields));
                    fclose($file);
                    return $this->redirect(['index']);
                }
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $importedCount = 0;
                    $errorRows = [];
                    $rowNumber = 1; // Empezamos en 1 para contar la fila de encabezados
                    
                    while (($row = fgetcsv($file)) !== false) {
                        $rowNumber++;
                        
                        // Verificar si la fila tiene suficientes columnas
                        if (count($row) < count($requiredFields)) {
                            $errorRows[] = "Fila {$rowNumber}: No tiene suficientes columnas";
                            continue;
                        }
                        
                        $producto = new Producto();
                        
                        // Asignar valores basados en los encabezados mapeados
                        foreach ($columnMap as $field => $index) {
                            // Verificar si el campo está definido y existe como atributo
                            if (isset($row[$index]) && $producto->hasAttribute($field)) {
                                $producto->$field = $row[$index];
                            }
                        }
                        
                        // Valores por defecto para campos que no estén en el CSV
                        if (!isset($columnMap['stock']) || empty($row[$columnMap['stock']])) {
                            $producto->stock = 0;
                        }
                        
                        if (!isset($columnMap['created_at']) || empty($row[$columnMap['created_at']])) {
                            $producto->created_at = date('Y-m-d H:i:s');
                        }
                        
                        // Intentar guardar el producto
                        if ($producto->validate() && $producto->save()) {
                            $importedCount++;
                        } else {
                            $errorRows[] = "Fila {$rowNumber}: " . implode(', ', $producto->getErrorSummary(true));
                        }
                    }
                    
                    // Si hay demasiados errores, revertir la transacción
                    if (count($errorRows) > 10) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Demasiados errores. Se encontraron ' . count($errorRows) . ' filas con problemas.');
                        
                        // Mostrar solo los primeros 10 errores
                        Yii::$app->session->setFlash('errorDetails', 'Primeros 10 errores: ' . implode('<br>', array_slice($errorRows, 0, 10)));
                        
                        return $this->redirect(['index']);
                    } elseif (count($errorRows) > 0) {
                        // Si hay algunos errores, pero no demasiados, comprometer la transacción y mostrar advertencias
                        $transaction->commit();
                        Yii::$app->session->setFlash('warning', 'Se importaron ' . $importedCount . ' productos, pero hubo ' . count($errorRows) . ' filas con errores.');
                        Yii::$app->session->setFlash('errorDetails', 'Errores: ' . implode('<br>', $errorRows));
                    } else {
                        
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Se importaron ' . $importedCount . ' productos correctamente.');
                    }
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error al importar: ' . $e->getMessage());
                    Yii::error('Error al importar CSV: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                }
                
                fclose($file);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Error de validación: ' . implode(', ', $model->getErrorSummary(true)));
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

/**
 * Imports productos data from a CSV file, incluyendo promociones.
 * @return mixed
 */
    public function actionImportFull()
    {
        $model = new \yii\base\DynamicModel([
            'csvFile' => null,
            'importarPromociones' => false,
        ]);
        $model->addRule(['csvFile'], 'file', ['extensions' => 'csv']);
        $model->addRule(['importarPromociones'], 'boolean');

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->csvFile = UploadedFile::getInstance($model, 'csvFile');

            if ($model->validate()) {
                $filePath = $model->csvFile->tempName;
                $file = fopen($filePath, 'r');
                
                // Leer encabezados
                $headers = fgetcsv($file);
                
                // Mapear índices de columnas
                $columnMap = [];
                foreach ($headers as $index => $header) {
                    $columnMap[trim(strtolower($header))] = $index;
                }
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $importedCount = 0;
                    $importedPromoCount = 0;
                    
                    while (($row = fgetcsv($file)) !== false) {
                        $producto = new Producto();
                        
                        // Asignar atributos básicos del producto
                        if (isset($columnMap['nombre']) && isset($row[$columnMap['nombre']])) {
                            $producto->nombre = $row[$columnMap['nombre']];
                        }
                        
                        if (isset($columnMap['descripcion']) && isset($row[$columnMap['descripcion']])) {
                            $producto->descripcion = $row[$columnMap['descripcion']];
                        }
                        
                        if (isset($columnMap['precio']) && isset($row[$columnMap['precio']])) {
                            $producto->precio = $row[$columnMap['precio']];
                        }
                        
                        if (isset($columnMap['tamaño']) && isset($row[$columnMap['tamaño']])) {
                            $producto->tamaño = $row[$columnMap['tamaño']];
                        }
                        
                        if ($producto->save()) {
                            $importedCount++;
                            
                            // Si está activada la opción de importar promociones
                            if ($model->importarPromociones && isset($columnMap['promociones']) && isset($row[$columnMap['promociones']])) {
                                $promocionesStr = $row[$columnMap['promociones']];
                                
                                if (!empty($promocionesStr)) {
                                    $promocionesList = explode('|', $promocionesStr);
                                    
                                    foreach ($promocionesList as $promoStr) {
                                        $promoData = explode(';', trim($promoStr));
                                        
                                        if (count($promoData) >= 4) { // nombre;descripcion;descuento;fecha_inicio;fecha_fin
                                            $promocion = new Promocion();
                                            $promocion->nombre = $promoData[0];
                                            $promocion->descripcion = $promoData[1];
                                            $promocion->descuento = $promoData[2];
                                            
                                            // Fechas en formato YYYY-MM-DD
                                            $fechas = explode(',', $promoData[3]); 
                                            if (count($fechas) >= 2) {
                                                $promocion->fecha_inicio = $fechas[0];
                                                $promocion->fecha_fin = $fechas[1];
                                            } else {
                                                // Fechas predeterminadas si no se proporcionan
                                                $promocion->fecha_inicio = date('Y-m-d');
                                                $promocion->fecha_fin = date('Y-m-d', strtotime('+30 days'));
                                            }
                                            
                                            $promocion->id_producto = $producto->id_producto;
                                            
                                            if ($promocion->save()) {
                                                $importedPromoCount++;
                                            } else {
                                                Yii::$app->session->setFlash('error', 'Error al importar promoción: ' . implode(', ', $promocion->getErrorSummary(true)));
                                                $transaction->rollBack();
                                                return $this->redirect(['index']);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'Error al importar producto: ' . implode(', ', $producto->getErrorSummary(true)));
                            $transaction->rollBack();
                            return $this->redirect(['index']);
                        }
                    }
                    
                    $transaction->commit();
                    $message = 'Se importaron ' . $importedCount . ' productos correctamente.';
                    if ($model->importarPromociones && $importedPromoCount > 0) {
                        $message .= ' También se importaron ' . $importedPromoCount . ' promociones.';
                    }
                    Yii::$app->session->setFlash('success', $message);
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error al importar: ' . $e->getMessage());
                }
                
                fclose($file);
                return $this->redirect(['index']);
            }
        }

        return $this->render('import-full', [
            'model' => $model,
        ]);
    }
 //------------------------------------------------------------FIN IMPORTA EN .csv

    

    /**
     * Finds the Producto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_producto ID
     * @return Producto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_producto)
    {
        if (($model = Producto::findOne(['id_producto' => $id_producto])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
  /**
 * Displays dashboard for Producto models.
 * @return string
 */
    public function actionDashboard()
    {
        // Obtener todos los productos
        $productos = Producto::find()->all();
        
        // Para el gráfico de precios por producto
        $nombresProductos = [];
        $preciosProductos = [];
        
        // Para el gráfico circular de tamaños
        $tamañosDatos = [];
        
        foreach($productos as $producto) {
            // Datos para el gráfico de barras de precios
            $nombresProductos[] = $producto->nombre;
            $preciosProductos[] = floatval($producto->precio);
            
            // Contabilizar tamaños para el gráfico circular
            if (!empty($producto->tamaño)) {
                if (!isset($tamañosDatos[$producto->tamaño])) {
                    $tamañosDatos[$producto->tamaño] = 0;
                }
                $tamañosDatos[$producto->tamaño]++;
            }
        }
        
        // Preparar datos para gráfico circular de tamaños
        $tamañosLabels = array_keys($tamañosDatos);
        $tamañosValores = array_values($tamañosDatos);
        
        // Obtener estadísticas para tarjetas informativas
        $totalProductos = count($productos);
        $precioPromedio = $totalProductos > 0 ? array_sum($preciosProductos) / $totalProductos : 0;
        $precioMax = !empty($preciosProductos) ? max($preciosProductos) : 0;
        $precioMin = !empty($preciosProductos) ? min($preciosProductos) : 0;
        
        // Obtener productos relacionados con promociones para una tabla adicional
        $productosConPromocion = Producto::find()
            ->innerJoin('promociones', 'promociones.id_producto = producto.id_producto')
            ->all();
        
        return $this->render('dashboard', [
            'productos' => $productos,
            'nombresProductos' => json_encode($nombresProductos),
            'preciosProductos' => json_encode($preciosProductos),
            'tamañosLabels' => json_encode($tamañosLabels),
            'tamañosValores' => json_encode($tamañosValores),
            'totalProductos' => $totalProductos,
            'precioPromedio' => $precioPromedio,
            'precioMax' => $precioMax,
            'precioMin' => $precioMin,
            'productosConPromocion' => $productosConPromocion
        ]);
    }
}