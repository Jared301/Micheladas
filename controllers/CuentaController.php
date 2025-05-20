<?php

namespace app\controllers;

use Yii;
use app\models\Cuenta;
use app\models\CuentaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * CuentaController implements the CRUD actions for Cuenta model.
 */
class CuentaController extends Controller
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
     * Lists all Cuenta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CuentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cuenta model.
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
     * Creates a new Cuenta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cuenta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_cuenta]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cuenta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_cuenta]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cuenta model.
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
        $searchModel = new CuentaSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $cuentas = $dataProvider->models;
        } else {
            $cuentas = Cuenta::find()->with(['venta', 'producto'])->all();
        }
        
        $content = $this->renderPartial('_pdf_view', [
            'cuentas' => $cuentas,
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
            'options' => ['title' => 'Detalle de Cuentas'],
            'methods' => [
                'SetHeader' => ['Detalle de Cuentas - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        return $pdf->render();
    }

    /**
     * Finds the Cuenta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cuenta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cuenta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
 * Imports cuenta data from a CSV file.
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
                $requiredFields = ['id_venta', 'id_producto'];
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
                        
                        $cuenta = new Cuenta();
                        
                        // Asignar valores basados en los encabezados mapeados
                        foreach ($columnMap as $field => $index) {
                            if (isset($row[$index]) && $cuenta->hasAttribute($field)) {
                                $cuenta->$field = $row[$index];
                            }
                        }
                        
                        // Intentar guardar
                        if ($cuenta->validate() && $cuenta->save()) {
                            $importedCount++;
                        } else {
                            $errorRows[] = "Fila {$rowNumber}: " . implode(', ', $cuenta->getErrorSummary(true));
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
}