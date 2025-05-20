<?php

namespace app\controllers;

use Yii;
use app\models\Ingrediente;
use app\models\IngredienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * IngredientesController implements the CRUD actions for Ingrediente model.
 */
class IngredientesController extends Controller
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
     * Lists all Ingrediente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IngredienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ingrediente model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Ingrediente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ingrediente();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_ingrediente]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ingrediente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_ingrediente]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ingrediente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Exports data to a PDF file.
     * @return mixed
     */
    public function actionExportPdf()
    {
        $searchModel = new IngredienteSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $ingredientes = $dataProvider->models;
        } else {
            $ingredientes = Ingrediente::find()->with('proveedor')->all();
        }
        
        $content = $this->renderPartial('_pdf_view', [
            'ingredientes' => $ingredientes,
        ]);
        
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
            'options' => ['title' => 'Lista de Ingredientes'],
            'methods' => [
                'SetHeader' => ['Lista de Ingredientes - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        return $pdf->render();
    }

    /**
     * Imports ingredientes data from a CSV file.
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
                $requiredFields = ['nombre'];
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
                        
                        $ingrediente = new Ingrediente();
                        
                        // Asignar valores basados en los encabezados mapeados
                        foreach ($columnMap as $field => $index) {
                            if (isset($row[$index]) && $ingrediente->hasAttribute($field)) {
                                $ingrediente->$field = $row[$index];
                            }
                        }
                        
                        // Intentar guardar
                        if ($ingrediente->validate() && $ingrediente->save()) {
                            $importedCount++;
                        } else {
                            $errorRows[] = "Fila {$rowNumber}: " . implode(', ', $ingrediente->getErrorSummary(true));
                        }
                    }
                    
                    if (count($errorRows) > 10) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Demasiados errores. Se encontraron ' . count($errorRows) . ' filas con problemas.');
                        Yii::$app->session->setFlash('errorDetails', 'Primeros 10 errores: ' . implode('<br>', array_slice($errorRows, 0, 10)));
                    } elseif (count($errorRows) > 0) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('warning', 'Se importaron ' . $importedCount . ' ingredientes, pero hubo ' . count($errorRows) . ' filas con errores.');
                        Yii::$app->session->setFlash('errorDetails', 'Errores: ' . implode('<br>', $errorRows));
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Se importaron ' . $importedCount . ' ingredientes correctamente.');
                    }
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error al importar: ' . $e->getMessage());
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
     * Finds the Ingrediente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ingrediente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ingrediente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}