<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Cliente;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['login', 'logout', 'dashboard'],
                'rules' => [
                    // 1) Login lo pueden hacer invitados
                    [
                        'actions' => ['login'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    // 2) Logout lo pueden hacer usuarios autenticados
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    // 3) Dashboard sólo para admin
                    [
                        'actions' => ['dashboard'],
                        'allow'   => true,
                        'roles'   => ['@'],
                        'matchCallback' => function($rule, $action) {
                            // Puedes usar ->identity->role o Yii::$app->user->can('viewDashboard')
                            return Yii::$app->user->identity->role === 'admin';
                        }
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        // Redirige a login si no está autenticado
                        return Yii::$app->response->redirect(['site/login']);
                    }
                    // Si está autenticado pero no es admin, lanza 403
                    throw new ForbiddenHttpException('No tienes permiso para acceder a esta página.');
                },
            ],
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionContact()
    {
        /* ... tu código de contacto ... */
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->role === 'admin') {
                return $this->redirect(['site/dashboard']);
            }
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionDashboard()
    {
        $clientes       = Cliente::find()->all();
        $totalClientes  = count($clientes);

        $dominiosCorreo = [];
        foreach ($clientes as $c) {
            if ($c->correo_electronico) {
                $d = substr(strrchr($c->correo_electronico, '@'), 1);
                $dominiosCorreo[$d] = ($dominiosCorreo[$d] ?? 0) + 1;
            }
        }

        $letraApellido = [];
        foreach ($clientes as $c) {
            if ($c->apellido) {
                $l = strtoupper($c->apellido[0]);
                $letraApellido[$l] = ($letraApellido[$l] ?? 0) + 1;
            }
        }
        ksort($letraApellido);

        return $this->render('dashboard', [
            'clientes'          => $clientes,
            'totalClientes'     => $totalClientes,
            'dominios'          => json_encode(array_keys($dominiosCorreo)),
            'cantidadesDominio' => json_encode(array_values($dominiosCorreo)),
            'letras'            => json_encode(array_keys($letraApellido)),
            'cantidadesLetra'   => json_encode(array_values($letraApellido)),
        ]);
    }
}
