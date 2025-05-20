<?php
namespace app\controllers;

use Yii;
use app\models\Venta;
use app\models\VentaSearch;
use app\models\DetalleVenta;
use app\models\Producto;
use app\models\Empleado;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

class VentasController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'=>[
                'class'=>VerbFilter::class,
                'actions'=>['delete'=>['POST']],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new VentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model    = $this->findModel($id);
        $detalles = DetalleVenta::find()
            ->where(['id_venta'=>$id])
            ->with('producto')
            ->all();
        return $this->render('view', [
            'model'=>$model,
            'detalles'=>$detalles,
        ]);
    }

    public function actionCreate()
    {
        $model     = new Venta();
        $model->fecha  = date('Y-m-d H:i:s');
        $model->total  = 0;
        $productos = Producto::find()->all();
        $empleados = Empleado::find()->all();
    
        if ($model->load(Yii::$app->request->post())) {
            $txn = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new \Exception('Error al guardar venta');
                }
                $total = 0;
                $sel   = Yii::$app->request->post('productos', []);
                $cant  = Yii::$app->request->post('cantidades', []);
                foreach ($sel as $i => $pid) {
                    if ($pid && isset($cant[$i]) && $cant[$i] > 0) {
                        $prod = Producto::findOne($pid);
                        $det  = new DetalleVenta([
                            'id_venta'        => $model->id_venta,
                            'id_producto'     => $pid,
                            'cantidad'        => $cant[$i],
                            'precio_unitario' => $prod->precio,
                        ]);
                        if (!$det->save()) {
                            throw new \Exception('Error al guardar detalle');
                        }
                        // Ajustar stock
                        $prod->stock -= $cant[$i];
                        $prod->save(false);
                        $total += $cant[$i] * $prod->precio;
                    }
                }
                $model->total = $total;
                $model->save(false);
    
                $txn->commit();
                // ← aquí redirigimos solo al índice de ventas
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $txn->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
    
        return $this->render('create', [
            'model'     => $model,
            'productos' => $productos,
            'empleados' => $empleados,
        ]);
    }

    public function actionUpdate($id)
    {
        $model     = $this->findModel($id);
        $productos = Producto::find()->all();
        $empleados = Empleado::find()->all();             // <-- Lista de empleados
        $detalles  = DetalleVenta::findAll(['id_venta'=>$id]);

        if ($model->load(Yii::$app->request->post())) {
            $txn = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                DetalleVenta::deleteAll(['id_venta'=>$id]);
                $total = 0;
                $sel   = Yii::$app->request->post('productos', []);
                $cant  = Yii::$app->request->post('cantidades', []);
                foreach ($sel as $i=>$pid) {
                    if ($pid && isset($cant[$i]) && $cant[$i]>0) {
                        $prod = Producto::findOne($pid);
                        $det  = new DetalleVenta([
                            'id_venta'        => $id,
                            'id_producto'     => $pid,
                            'cantidad'        => $cant[$i],
                            'precio_unitario' => $prod->precio,
                        ]);
                        $det->save(false);
                        $total += $cant[$i]*$prod->precio;
                    }
                }
                $model->total = $total;
                $model->save(false);
                $txn->commit();
                return $this->redirect(['view','id'=>$id]);
            } catch (\Exception $e) {
                $txn->rollBack();
                Yii::$app->session->setFlash('error',$e->getMessage());
            }
        }

        return $this->render('update', [
            'model'     => $model,
            'productos' => $productos,
            'empleados' => $empleados,  // <-- Y aquí también
            'detalles'  => $detalles,
        ]);
    }

    public function actionDelete($id)
    {
        $txn = Yii::$app->db->beginTransaction();
        try {
            DetalleVenta::deleteAll(['id_venta'=>$id]);
            $this->findModel($id)->delete();
            $txn->commit();
        } catch (\Exception $e) {
            $txn->rollBack();
            Yii::$app->session->setFlash('error',$e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionTicket($id)
    {
        $venta    = $this->findModel($id);
        $detalles = DetalleVenta::find()->where(['id_venta'=>$id])->with('producto')->all();
        $content  = $this->renderPartial('_ticket', ['venta'=>$venta,'detalles'=>$detalles]);

        $pdf = new Pdf([
            'mode'        => Pdf::MODE_CORE,
            'format'      => [80,150],
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content'     => $content,
            'cssInline'   => ".ticket{width:280px;font-size:12px;} .ticket h2{text-align:center;} .ticket table{width:100%;border-collapse:collapse;} .ticket th,.ticket td{padding:4px;text-align:left;}",
            'methods'     => [
                'SetHeader'=>[''],
                'SetFooter'=>['¡Gracias por su compra! {PAGENO}'],
            ],
        ]);

        return $pdf->render();
    }

    protected function findModel($id)
    {
        if (($m = Venta::findOne($id))!==null) return $m;
        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
