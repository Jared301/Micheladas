<?php

namespace app\controllers;

use Yii;
use app\models\Promocion;
use app\models\PromocionSearch;
use app\models\Producto;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * PromocionesController implements the CRUD actions for Promocion model.
 */
class PromocionesController extends Controller
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
     * Lists all Promocion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PromocionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Promocion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_promocion)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_promocion),
        ]);
    }

    /**
     * Creates a new Promocion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Promocion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_promocion]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Promocion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_promocion)
    {
        $model = $this->findModel($id_promocion);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_promocion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Promocion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_promocion)
    {
        $this->findModel($id_promocion)->delete();

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
                        $promocion = new Promocion();
                        
                        // Asignar valores a atributos de promoción
                        if (isset($columnMap['nombre']) && isset($row[$columnMap['nombre']])) {
                            $promocion->nombre = $row[$columnMap['nombre']];
                        }
                        
                        if (isset($columnMap['descripcion']) && isset($row[$columnMap['descripcion']])) {
                            $promocion->descripcion = $row[$columnMap['descripcion']];
                        }
                        
                        if (isset($columnMap['descuento']) && isset($row[$columnMap['descuento']])) {
                            $promocion->descuento = $row[$columnMap['descuento']];
                        }
                        
                        if (isset($columnMap['fecha_inicio']) && isset($row[$columnMap['fecha_inicio']])) {
                            $promocion->fecha_inicio = $row[$columnMap['fecha_inicio']];
                        }
                        
                        if (isset($columnMap['fecha_fin']) && isset($row[$columnMap['fecha_fin']])) {
                            $promocion->fecha_fin = $row[$columnMap['fecha_fin']];
                        }
                        
                        // Verificar si es un ID o nombre de producto
                        $id_producto_o_nombre = null;
                        if (isset($columnMap['id_producto_o_nombre']) && isset($row[$columnMap['id_producto_o_nombre']])) {
                            $id_producto_o_nombre = $row[$columnMap['id_producto_o_nombre']];
                        } else if (isset($columnMap['id_producto']) && isset($row[$columnMap['id_producto']])) {
                            $id_producto_o_nombre = $row[$columnMap['id_producto']];
                        }
                        
                        $id_producto = null;
                        if (is_numeric($id_producto_o_nombre)) {
                            $id_producto = $id_producto_o_nombre;
                        } else {
                            // Buscar el producto por nombre
                            $producto = Producto::findOne(['nombre' => $id_producto_o_nombre]);
                            if ($producto) {
                                $id_producto = $producto->id_producto;
                            }
                        }
                        
                        // Solo importar si se encontró el producto
                        if ($id_producto) {
                            $promocion->id_producto = $id_producto;
                            
                            if ($promocion->save()) {
                                $importedCount++;
                            } else {
                                Yii::$app->session->setFlash('error', 'Error al importar la fila: ' . implode(', ', $promocion->getErrorSummary(true)));
                                $transaction->rollBack();
                                return $this->redirect(['index']);
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'No se encontró el producto con el dato: ' . $id_producto_o_nombre);
                            $transaction->rollBack();
                            return $this->redirect(['index']);
                        }
                    }
                    
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Se importaron ' . $importedCount . ' promociones correctamente.');
                    
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
        // Si quieres exportar las promociones filtradas
        $searchModel = new PromocionSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $promociones = $dataProvider->models;
        } else {
            // Si no hay filtros, obtener todas las promociones
            $promociones = Promocion::find()->with('producto')->all();
        }
        
        // Preparar contenido HTML para el PDF
        $content = $this->renderPartial('_pdf_view', [
            'promociones' => $promociones,
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
            'options' => ['title' => 'Lista de Promociones'],
            'methods' => [
                'SetHeader' => ['Lista de Promociones - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        // Devolver el PDF al navegador
        return $pdf->render();
    }

    /**
     * Finds the Promocion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promocion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promocion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}