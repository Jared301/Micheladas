<?php

namespace app\controllers;

use Yii;
use app\models\Proveedores;
use app\models\ProveedoresSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

class ProveedoresController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel  = new ProveedoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_proveedor)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_proveedor),
        ]);
    }

    /**
     * Creates a new Proveedores model.
     * If creation is successful, redirects to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Proveedores();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_proveedor' => $model->id_proveedor]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id_proveedor)
    {
        $model = $this->findModel($id_proveedor);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_proveedor' => $model->id_proveedor]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id_proveedor)
    {
        $this->findModel($id_proveedor)->delete();

        return $this->redirect(['index']);
    }

    // ... aquí van tus métodos actionExportPdf, actionImportar, actionDashboard, etc., sin cambios ...

    protected function findModel($id_proveedor)
    {
        if (($model = Proveedores::findOne(['id_proveedor' => $id_proveedor])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
