<?php

namespace app\controllers;

use Yii;
use app\models\Cliente;
use app\models\ClienteSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;


/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClientesController extends Controller
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
     * Lists all Cliente models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel'=> $searchModel,
            'dataProvider'=> $dataProvider
        ]);
    }

    /**
     * Displays a single Cliente model.
     * @param int $id_cliente ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_cliente)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_cliente),
        ]);
    }

    /**
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cliente();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_cliente' => $model->id_cliente]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_cliente ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_cliente)
    {
        $model = $this->findModel($id_cliente);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_cliente' => $model->id_cliente]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_cliente ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_cliente)
    {
        $this->findModel($id_cliente)->delete();

        return $this->redirect(['index']);
    }


    //----------------------------Para exportar a PDF ----------------------------------------------------
    
    public function actionExportPdf()
    {
        // Si quieres exportar los clientes filtrados (si estás utilizando un modelo de búsqueda)
        // y estás pasando parámetros de filtro en la URL
        $searchModel = new ClienteSearch();
        $params = Yii::$app->request->queryParams;
        
        if (!empty($params)) {
            $dataProvider = $searchModel->search($params);
            $clientes = $dataProvider->models;
        } else {
            // Si no hay filtros, obtener todos los clientes
            $clientes = Cliente::find()->all();
        }
        
        // Preparar contenido HTML para el PDF
        $content = $this->renderPartial('_pdf_view', [
            'clientes' => $clientes,
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
            'options' => ['title' => 'Lista de Clientes'],
            'methods' => [
                'SetHeader' => ['Lista de Clientes - ' . date('d/m/Y')],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);
        
        // Devolver el PDF al navegador
        return $pdf->render();
    }
    //---------------------------------FIN exportar PDF ------------------------------------------------------------------------



    //--------------------------------IMPORTA desde un archivo .csv --------------------------------------------------------------
    // Aqui se modifica dependiendo de cada modelo, es decir para cada tabla, en cada controlador que se crea se debera pegar esta arte del codigo 
    public function actionImportar()
    {
        if (Yii::$app->request->isPost) {
            $archivo = UploadedFile::getInstanceByName('archivo_csv');

            if ($archivo) {
                try {
                    // Asegurarse que el directorio existe
                    $uploadPath = Yii::getAlias('@app/uploads');
                    if (!file_exists($uploadPath)) { // si no existe lo crea
                        mkdir($uploadPath, 0777, true);
                    }
                    
                    
                    $rutaCompleta = $uploadPath . '/' . $archivo->baseName . '.' . $archivo->extension;
                    if ($archivo->saveAs($rutaCompleta)) {
                        $handle = fopen($rutaCompleta, "r");
                        fgetcsv($handle); // Saltar la primera fila (encabezados)
                        
                        $importados = 0;
                        $errores = 0;
                        
                        //--------Modifica aqui conforme a las columas de cada tabla de nuestra base de detos---------------------------
                        while (($datos = fgetcsv($handle, 1000, ",")) !== false) {
                            if (count($datos) >= 5) {  // Verificar que tenga suficientes columnas
                                $cliente = Cliente::findOne($datos[0]) ?? new Cliente();
                                $cliente->nombre = $datos[1];
                                $cliente->apellido = $datos[2];
                                $cliente->telefono = $datos[3];
                                $cliente->correo_electronico = $datos[4];
                                $cliente->direccion = isset($datos[5]) ? $datos[5] : '';
                                
                                if ($cliente->save()) {
                                    $importados++;
                                } else {
                                    $errores++;
                                    Yii::error('Error al guardar cliente: ' . json_encode($cliente->errors));
                                }
                            }
                        }
                        
                        fclose($handle);
                        unlink($rutaCompleta);
                        
                        if ($errores > 0) {
                            Yii::$app->session->setFlash('warning', "Importación completada con advertencias: $importados clientes importados, $errores con errores.");
                        } else {
                            Yii::$app->session->setFlash('success', "$importados clientes importados correctamente.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'No se pudo guardar el archivo.');
                    }
                } catch (\Exception $e) {
                    Yii::error('Error en importación: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', 'Error al procesar el archivo: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'No se ha seleccionado ningún archivo.');
            }
        }

        return $this->redirect(['index']);
    }
 //------------------------------------------------------------FIN IMPORTA EN .csv

    

    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_cliente ID
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_cliente)
    {
        if (($model = Cliente::findOne(['id_cliente' => $id_cliente])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Displays dashboard for Cliente models.
     * @return string
     */
    /**
 * Displays dashboard for Cliente models.
 * @return string
 */
    public function actionDashboard()
    {
        // Obtener todos los clientes
        $clientes = Cliente::find()->all();
        
        // Estadísticas básicas
        $totalClientes = count($clientes);
        
        // Distribución por dominio de correo electrónico
        $dominiosCorreo = [];
        foreach($clientes as $cliente) {
            if (!empty($cliente->correo_electronico)) {
                $partes = explode('@', $cliente->correo_electronico);
                if (count($partes) > 1) {
                    $dominio = $partes[1];
                    if (!isset($dominiosCorreo[$dominio])) {
                        $dominiosCorreo[$dominio] = 0;
                    }
                    $dominiosCorreo[$dominio]++;
                }
            }
        }
        
        // Distribución por primera letra del apellido
        $letraApellido = [];
        foreach($clientes as $cliente) {
            if (!empty($cliente->apellido)) {
                $primeraLetra = strtoupper(substr($cliente->apellido, 0, 1));
                if (!isset($letraApellido[$primeraLetra])) {
                    $letraApellido[$primeraLetra] = 0;
                }
                $letraApellido[$primeraLetra]++;
            }
        }
        
        // Ordenar por letra
        ksort($letraApellido);
        
        // Separar en arrays para los gráficos
        $letras = array_keys($letraApellido);
        $cantidadesLetra = array_values($letraApellido);
        
        // Dominios para el gráfico circular
        arsort($dominiosCorreo);
        $dominios = array_keys($dominiosCorreo);
        $cantidadesDominio = array_values($dominiosCorreo);
        
        return $this->render('dashboard', [
            'clientes' => $clientes,
            'totalClientes' => $totalClientes,
            'dominios' => json_encode($dominios),
            'cantidadesDominio' => json_encode($cantidadesDominio),
            'letras' => json_encode($letras),
            'cantidadesLetra' => json_encode($cantidadesLetra)
        ]);
    }
}