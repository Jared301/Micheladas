<?php

namespace app\controllers;

use Yii;
use app\models\Mesa;
use app\models\MesaSearch;
use app\models\Empleado;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * MesasController implements the CRUD actions for Mesa model.
 */
class MesasController extends Controller
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
     * Lists all Mesa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MesaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mesa model.
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
     * Creates a new Mesa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mesa();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_mesa]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mesa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_mesa]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mesa model.
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
     * Imports data from a CSV file.
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
                $file = fopen($filePath, 'r');
                
                // Skip header row
                $headers = fgetcsv($file);
                
                // Mapear índices de columnas
                $columnMap = [];
                foreach ($headers as $index => $header) {
                    $columnMap[trim(strtolower($header))] = $index;
                }
                
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $importedCount = 0;
                    
                    while (($row = fgetcsv($file)) !== false) {
                        // Asumimos que el CSV tiene este orden o utiliza los encabezados:
                        // numero_mesa, capacidad, estado, id_empleado_o_correo
                        
                        $mesa = new Mesa();
                        
                        if (isset($columnMap['numero_mesa']) && isset($row[$columnMap['numero_mesa']])) {
                            $mesa->numero_mesa = $row[$columnMap['numero_mesa']];
                        } else {
                            $mesa->numero_mesa = $row[0]; // Si no hay encabezado, asume posición 0
                        }
                        
                        if (isset($columnMap['capacidad']) && isset($row[$columnMap['capacidad']])) {
                            $mesa->capacidad = $row[$columnMap['capacidad']];
                        } else {
                            $mesa->capacidad = $row[1]; // Si no hay encabezado, asume posición 1
                        }
                        
                        if (isset($columnMap['estado']) && isset($row[$columnMap['estado']])) {
                            $mesa->estado = $row[$columnMap['estado']];
                        } else {
                            $mesa->estado = $row[2]; // Si no hay encabezado, asume posición 2
                        }
                        
                        // Verificar si es un ID o un correo electrónico
                        $id_empleado_o_correo = null;
                        if (isset($columnMap['id_empleado_o_correo']) && isset($row[$columnMap['id_empleado_o_correo']])) {
                            $id_empleado_o_correo = $row[$columnMap['id_empleado_o_correo']];
                        } else {
                            $id_empleado_o_correo = $row[3]; // Si no hay encabezado, asume posición 3
                        }
                        
                        $id_empleado = null;
                        if (is_numeric($id_empleado_o_correo)) {
                            $id_empleado = $id_empleado_o_correo;
                        } else {
                            // Buscar el empleado por correo electrónico
                            $empleado = Empleado::findOne(['correo_electronico' => $id_empleado_o_correo]);
                            if ($empleado) {
                                $id_empleado = $empleado->id_empleado;
                            }
                        }
                        
                        // Solo importar si se encontró el empleado
                        if ($id_empleado) {
                            $mesa->id_empleado = $id_empleado;
                            
                            if ($mesa->save()) {
                                $importedCount++;
                            } else {
                                Yii::$app->session->setFlash('error', 'Error al importar la fila: ' . implode(', ', $mesa->getErrorSummary(true)));
                                $transaction->rollBack();
                                return $this->redirect(['index']);
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'No se encontró el empleado con el dato: ' . $id_empleado_o_correo);
                            $transaction->rollBack();
                            return $this->redirect(['index']);
                        }
                    }
                    
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Se importaron ' . $importedCount . ' mesas correctamente.');
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error al importar: ' . $e->getMessage());
                }
                
                fclose($file);
                return $this->redirect(['index']);
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
        // Si quieres exportar las mesas filtradas
        $searchModel = new MesaSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $mesas = $dataProvider->models;
        } else {
            // Si no hay filtros, obtener todas las mesas
            $mesas = Mesa::find()->with('empleado')->all();
        }
        
        // Preparar contenido HTML para el PDF
        $content = $this->renderPartial('_pdf_view', [
            'mesas' => $mesas,
        ]);
        
        // Configurar el PDF
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE, // Horizontal para mostrar más columnas
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',//Esta Parte da error no encuentra el archivo .css 
            'cssInline' => '
                .kv-heading-1 { font-size:18px }
                table { width: 100%; border-collapse: collapse; }
                th, td { padding: 6px; border: 1px solid #ccc; }
                th { background-color: #f0f0f0; }
            ',
            'options' => ['title' => 'Lista de Mesas'],
            'methods' => [
                'SetHeader' => ['Lista de Mesas - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        // Devolver el PDF al navegador
        return $pdf->render();
    }

    /**
     * Finds the Mesa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mesa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mesa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}