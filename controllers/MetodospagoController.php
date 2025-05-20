<?php

namespace app\controllers;

use Yii;
use app\models\Metodospago;
use app\models\MetodospagoSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * MetodospagoController implements the CRUD actions for Metodospago model.
 */
class MetodospagoController extends Controller
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
     * Lists all Metodospago models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MetodospagoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel'=> $searchModel,
            'dataProvider'=> $dataProvider
        ]);
    }

    /**
     * Displays a single Metodospago model.
     * @param int $id_metodo_pago ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_metodo_pago)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_metodo_pago),
        ]);
    }

    /**
     * Creates a new Metodospago model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Metodospago();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_metodo_pago' => $model->id_metodo_pago]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Metodospago model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_metodo_pago ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_metodo_pago)
    {
        $model = $this->findModel($id_metodo_pago);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_metodo_pago' => $model->id_metodo_pago]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Metodospago model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_metodo_pago ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_metodo_pago)
    {
        $this->findModel($id_metodo_pago)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Exports data to a PDF file.
     * @return mixed
     */
    public function actionExportPdf()
    {
        $searchModel = new MetodospagoSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $metodosPago = $dataProvider->models;
        } else {
            $metodosPago = Metodospago::find()->all();
        }
        
        // Preparar contenido HTML para el PDF
        $content = $this->renderPartial('_pdf_view', [
            'metodosPago' => $metodosPago,
        ]);
        
        // Configurar el PDF
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => '
                .kv-heading-1 { font-size:18px; text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 6px; border: 1px solid #ccc; }
                th { background-color: #f0f0f0; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            ',
            'options' => ['title' => 'Lista de Métodos de Pago'],
            'methods' => [
                'SetHeader' => ['Lista de Métodos de Pago - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        // Devolver el PDF al navegador
        return $pdf->render();
    }

    /**
     * Imports metodos de pago data from a CSV file.
     * @return mixed
     */
    public function actionImport()
    {
        $model = new \yii\base\DynamicModel([
            'csvFile' => null,
        ]);
        $model->addRule(['csvFile'], 'file', [
            'extensions' => 'csv', 
            'checkExtensionByMimeType' => false,
            'maxSize' => 1024 * 1024, // 1MB
            'tooBig' => 'El archivo no puede ser mayor a 1MB'
        ]);
    
        if (Yii::$app->request->isPost) {
            $model->csvFile = UploadedFile::getInstance($model, 'csvFile');
    
            if ($model->csvFile !== null) {
                // Verificar explícitamente la extensión del archivo
                $extension = strtolower(pathinfo($model->csvFile->name, PATHINFO_EXTENSION));
                if ($extension !== 'csv') {
                    Yii::$app->session->setFlash('error', 'Solo se permiten archivos con extensión .csv');
                    return $this->render('import', [
                        'model' => $model,
                    ]);
                }
    
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
                    $requiredFields = ['tipo'];
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
                            
                            $metodoPago = new Metodospago();
                            
                            // Asignar valores basados en los encabezados mapeados
                            foreach ($columnMap as $field => $index) {
                                // Verificar si el campo está definido y existe como atributo
                                if (isset($row[$index]) && $metodoPago->hasAttribute($field)) {
                                    $metodoPago->$field = $row[$index];
                                }
                            }
                            
                            // Intentar guardar el método de pago
                            if ($metodoPago->validate() && $metodoPago->save()) {
                                $importedCount++;
                            } else {
                                $errorRows[] = "Fila {$rowNumber}: " . implode(', ', $metodoPago->getErrorSummary(true));
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
                            Yii::$app->session->setFlash('warning', 'Se importaron ' . $importedCount . ' métodos de pago, pero hubo ' . count($errorRows) . ' filas con errores.');
                            Yii::$app->session->setFlash('errorDetails', 'Errores: ' . implode('<br>', $errorRows));
                        } else {
                            
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Se importaron ' . $importedCount . ' métodos de pago correctamente.');
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
            } else {
                Yii::$app->session->setFlash('error', 'Por favor selecciona un archivo CSV para importar.');
            }
        }
    
        return $this->render('import', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Metodospago model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_metodo_pago ID
     * @return Metodospago the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_metodo_pago)
    {
        if (($model = Metodospago::findOne(['id_metodo_pago' => $id_metodo_pago])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Displays dashboard for Metodos_de_Pago models.
     * @return string
     */
    /**
 * Displays dashboard for Metodos_de_Pago models.
 * @return string
 */
    public function actionDashboard()
    {
        // Obtener todos los métodos de pago
        $metodosPago = MetodosPago::find()->all();
        
        // Estadísticas básicas
        $totalMetodos = count($metodosPago);
        
        // Datos para las gráficas
        // Si no hay datos de ventas reales, usamos datos de ejemplo
        $tiposMetodos = [];
        $ventasPorMetodo = [];
        $tiposMetodosValor = [];
        $valoresPorMetodo = [];
        
        // Extraer nombres de tipos de pago
        foreach($metodosPago as $metodo) {
            $tiposMetodos[] = $metodo->tipo;
            $tiposMetodosValor[] = $metodo->tipo;
            
            // Generar datos aleatorios para el ejemplo
            // En producción, estos datos deberían venir de las ventas reales
            $ventasPorMetodo[] = rand(5, 50); // Número aleatorio entre 5 y 50
            $valoresPorMetodo[] = rand(1000, 50000); // Valor aleatorio entre 1000 y 50000
        }
        
        return $this->render('dashboard', [
            'metodosPago' => $metodosPago,
            'totalMetodos' => $totalMetodos,
            'tiposMetodos' => json_encode($tiposMetodos),
            'ventasPorMetodo' => json_encode($ventasPorMetodo),
            'tiposMetodosValor' => json_encode($tiposMetodosValor),
            'valoresPorMetodo' => json_encode($valoresPorMetodo)
        ]);
    }
}