<?php

namespace app\controllers;

use Yii;
use app\models\ProductoIngrediente;
use app\models\ProductoIngredienteSearch;
use app\models\Producto;
use app\models\Ingrediente;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * ProductoIngredienteController implements the CRUD actions for ProductoIngrediente model.
 */
class ProductoIngredienteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProductoIngrediente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductoIngredienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductoIngrediente model.
     * @param integer $id_producto
     * @param integer $id_ingrediente
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_producto, $id_ingrediente)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_producto, $id_ingrediente),
        ]);
    }

    /**
     * Creates a new ProductoIngrediente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductoIngrediente();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductoIngrediente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_producto
     * @param integer $id_ingrediente
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_producto, $id_ingrediente)
    {
        $model = $this->findModel($id_producto, $id_ingrediente);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_producto' => $model->id_producto, 'id_ingrediente' => $model->id_ingrediente]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductoIngrediente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_producto
     * @param integer $id_ingrediente
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_producto, $id_ingrediente)
    {
        $this->findModel($id_producto, $id_ingrediente)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Imports producto_ingrediente data from a CSV file.
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
            'maxSize' => 1024 * 1024,
        ]);

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
                
                // Mapear índices de columnas
                $columnMap = [];
                foreach ($headers as $index => $header) {
                    $header = trim(mb_strtolower($header, 'UTF-8'));
                    $columnMap[$header] = $index;
                }
                
                // Verificar si los encabezados requeridos están presentes
                $requiredFields = ['id_producto', 'id_ingrediente'];
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
                        
                        $id_producto = $row[$columnMap['id_producto']];
                        $id_ingrediente = $row[$columnMap['id_ingrediente']];
                        
                        // Verificar si ya existe esta relación
                        $productoIngrediente = ProductoIngrediente::findOne(['id_producto' => $id_producto, 'id_ingrediente' => $id_ingrediente]);
                        
                        if (!$productoIngrediente) {
                            $productoIngrediente = new ProductoIngrediente();
                            $productoIngrediente->id_producto = $id_producto;
                            $productoIngrediente->id_ingrediente = $id_ingrediente;
                        }
                        
                        // Asignar cantidad si está presente
                        if (isset($columnMap['cantidad']) && isset($row[$columnMap['cantidad']])) {
                            $productoIngrediente->cantidad = $row[$columnMap['cantidad']];
                        }
                        
                        // Intentar guardar
                        if ($productoIngrediente->validate() && $productoIngrediente->save()) {
                            $importedCount++;
                        } else {
                            $errorRows[] = "Fila {$rowNumber}: " . implode(', ', $productoIngrediente->getErrorSummary(true));
                        }
                    }
                    
                    if (count($errorRows) > 10) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Demasiados errores. Se encontraron ' . count($errorRows) . ' filas con problemas.');
                        Yii::$app->session->setFlash('errorDetails', 'Primeros 10 errores: ' . implode('<br>', array_slice($errorRows, 0, 10)));
                    } elseif (count($errorRows) > 0) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('warning', 'Se importaron ' . $importedCount . ' registros, pero hubo ' . count($errorRows) . ' filas con errores.');
                        Yii::$app->session->setFlash('errorDetails', 'Errores: ' . implode('<br>', $errorRows));
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Se importaron ' . $importedCount . ' registros correctamente.');
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
     * Exports data to a PDF file.
     * @return mixed
     */
    public function actionExportPdf()
    {
        $searchModel = new ProductoIngredienteSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $models = $dataProvider->models;
        } else {
            $models = ProductoIngrediente::find()->with(['producto', 'ingrediente'])->all();
        }
        
        $content = $this->renderPartial('_pdf_view', [
            'models' => $models,
        ]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => '
                .kv-heading-1 { font-size:18px; text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 6px; border: 1px solid #ccc; }
                th { background-color: #f0f0f0; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            ',
            'options' => ['title' => 'Listado de Ingredientes por Producto'],
            'methods' => [
                'SetHeader' => ['Ingredientes por Producto - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        return $pdf->render();
    }

    /**
     * Finds the ProductoIngrediente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_producto
     * @param integer $id_ingrediente
     * @return ProductoIngrediente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_producto, $id_ingrediente)
    {
        if (($model = ProductoIngrediente::findOne(['id_producto' => $id_producto, 'id_ingrediente' => $id_ingrediente])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}