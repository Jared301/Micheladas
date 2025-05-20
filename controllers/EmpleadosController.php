<?php

namespace app\controllers;

use Yii;
use app\models\Empleado;
use app\models\EmpleadoSearch;
use app\models\Mesa;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use yii\helpers\Html;

/**
 * EmpleadosController implements the CRUD actions for Empleado model.
 */
class EmpleadosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Empleado models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new EmpleadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Empleado model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Empleado model.
     * If creation is successful, redirects to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Empleado(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_empleado]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Empleado model.
     * If update is successful, redirects to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id_empleado]);
            }
            // Mostrar errores si el save() falla
            Yii::$app->session->setFlash('error',
                'Error al actualizar:<br>' . Html::errorSummary($model)
            );
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Empleado model.
     * If deletion is successful, redirects to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Imports empleados data from a CSV file (optionally with related mesas).
     * @return mixed
     */
    public function actionImport()
    {
        $model = new \yii\base\DynamicModel([
            'csvFile'       => null,
            'importarMesas' => false,
        ]);
        $model->addRule(['csvFile'], 'file', ['extensions' => 'csv']);
        $model->addRule(['importarMesas'], 'boolean');

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->csvFile = UploadedFile::getInstance($model, 'csvFile');

            if ($model->validate()) {
                $file     = fopen($model->csvFile->tempName, 'r');
                $headers  = fgetcsv($file);
                $columnMap = [];
                foreach ($headers as $i => $h) {
                    $columnMap[trim(strtolower($h))] = $i;
                }

                $tx             = Yii::$app->db->beginTransaction();
                $countEmp       = 0;
                $countMesas     = 0;

                try {
                    while (($row = fgetcsv($file)) !== false) {
                        $emp = new Empleado(['scenario' => 'create']);

                        if (isset($columnMap['nombre'])) {
                            $emp->nombre = $row[$columnMap['nombre']];
                        }
                        if (isset($columnMap['apellido'])) {
                            $emp->apellido = $row[$columnMap['apellido']];
                        }
                        if (isset($columnMap['puesto'])) {
                            $emp->puesto = $row[$columnMap['puesto']];
                        }
                        if (isset($columnMap['telefono'])) {
                            $emp->telefono = $row[$columnMap['telefono']];
                        }
                        if (isset($columnMap['correo_electronico'])) {
                            $emp->correo_electronico = $row[$columnMap['correo_electronico']];
                        }
                        if (isset($columnMap['salario'])) {
                            $emp->salario = $row[$columnMap['salario']];
                        }

                        if (!$emp->save()) {
                            throw new \Exception("Empleado inválido: " . implode(', ', $emp->getErrorSummary(true)));
                        }
                        $countEmp++;

                        if ($model->importarMesas && isset($columnMap['mesas'])) {
                            foreach (explode(',', $row[$columnMap['mesas']]) as $ms) {
                                $mesaData = explode(':', trim($ms)) + [null, null, null];
                                list($num, $cap, $est) = $mesaData;
                                if ($num !== null) {
                                    $mesa = new Mesa();
                                    $mesa->numero_mesa = $num;
                                    $mesa->capacidad   = $cap;
                                    $mesa->estado      = $est;
                                    $mesa->id_empleado = $emp->id_empleado;
                                    if (!$mesa->save()) {
                                        throw new \Exception("Mesa inválida: " . implode(', ', $mesa->getErrorSummary(true)));
                                    }
                                    $countMesas++;
                                }
                            }
                        }
                    }

                    $tx->commit();
                    Yii::$app->session->setFlash('success',
                        "Importados $countEmp empleados" .
                        ($model->importarMesas ? " y $countMesas mesas" : "")
                    );
                } catch (\Exception $e) {
                    $tx->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
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
     * Exports empleados (sin mesas) to PDF.
     * @return mixed
     */
    public function actionExportPdf()
    {
        $searchModel = new EmpleadoSearch();
        $params      = Yii::$app->request->queryParams;
        $models      = !empty($params)
            ? $searchModel->search($params)->models
            : Empleado::find()->all();

        $content = $this->renderPartial('_pdf_view', [
            'empleados'    => $models,
            'mostrarMesas' => false,
        ]);

        $pdf = new Pdf([
            'mode'        => Pdf::MODE_CORE,
            'format'      => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content'     => $content,
            'cssInline'   => '
                .kv-heading-1{font-size:18px;}
                h3{color:#2a6496;margin-top:20px;}
                table{width:100%;border-collapse:collapse;margin-bottom:15px;}
                th,td{padding:6px;border:1px solid #ccc;}
                th{background:#f0f0f0;}
            ',
            'options' => ['title' => 'Lista de Empleados'],
            'methods' => [
                'SetHeader'=>['Lista de Empleados - '.date('d/m/Y')],
                'SetFooter'=>['{PAGENO}'],
            ],
        ]);

        return $pdf->render();
    }

    /**
     * Displays dashboard for Empleado models.
     * @return string
     */
    public function actionDashboard()
    {
        $empleados      = Empleado::find()->all();
        $totalEmpleados = count($empleados);

        // Distribución por puesto
        $puestosMap = [];
        foreach ($empleados as $e) {
            if ($e->puesto) {
                $puestosMap[$e->puesto] = ($puestosMap[$e->puesto] ?? 0) + 1;
            }
        }

        // Ventas por empleado
        $ventasPorEmpleado = Empleado::find()
            ->select([
                'empleados.nombre',
                'empleados.apellido',
                'COUNT(ventas.id_venta) AS total_ventas'
            ])
            ->leftJoin('ventas', 'ventas.id_empleado=empleados.id_empleado')
            ->groupBy('empleados.id_empleado')
            ->asArray()->all();

        $rangos      = ['0-5000','5001-10000','10001-15000','15001-20000','20001+'];
        $rangoCounts = array_fill(0, count($rangos), 0);
        foreach ($empleados as $e) {
            $s = (float)$e->salario;
            if ($s <= 5000)      $rangoCounts[0]++;
            elseif ($s <= 10000) $rangoCounts[1]++;
            elseif ($s <= 15000) $rangoCounts[2]++;
            elseif ($s <= 20000) $rangoCounts[3]++;
            else                  $rangoCounts[4]++;
        }

        return $this->render('dashboard', [
            'empleados'        => $empleados,
            'totalEmpleados'   => $totalEmpleados,
            'puestos'          => json_encode(array_keys($puestosMap)),
            'cantidadPorPuesto'=> json_encode(array_values($puestosMap)),
            'nombresEmpleados' => json_encode(array_column($ventasPorEmpleado, 'nombre')),
            'ventasEmpleados'  => json_encode(array_column($ventasPorEmpleado, 'total_ventas')),
            'rangosSalariales' => json_encode($rangos),
            'cantidadPorRango' => json_encode($rangoCounts),
        ]);
    }

    /**
     * Finds the Empleado model based on its primary key value.
     * @param integer $id
     * @return Empleado
     * @throws NotFoundHttpException if not found
     */
    protected function findModel($id)
    {
        if (($model = Empleado::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('El empleado solicitado no existe.');
    }
}
