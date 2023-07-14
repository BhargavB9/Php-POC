<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\LoginForm;
use app\models\EmployeeTb;
use app\models\Admin;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'only' => ['employeedetails', 'create', 'edit'],
            'rules' => [
                [
                    'actions' => ['employeedetails', 'create', 'edit'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                return Yii::$app->response->redirect(['site/login']);
            },
        ],
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'logout' => ['post'],
            ],
        ],
    ];
}


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * login
     */
     public function actionLogin()
     {
         $model = new LoginForm();
     
         if ($model->load(Yii::$app->request->post()) && $model->login()) {
             $adminName = Yii::$app->user->identity->username;
             $loginTime = date('Y-m-d H:i:s');
             
             EmployeeTb::updateAll([
                 'last_loggedin_admin' => $adminName,
                 'login_time' => $loginTime,
             ]);
     
             return $this->redirect(['employeedetails']);
         }
     
         return $this->render('login', [
             'model' => $model,
         ]);
     }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
{
    Yii::$app->user->logout();

    Yii::$app->session->setFlash('success', 'You Logged out Succesfully.');

    return $this->goHome();
}

    /**
     * Displays employeedetails page.
     */
    public function actionEmployeedetails()
    {
        $employees = EmployeeTb::find()->orderBy(['updated' => SORT_DESC])->all();

        return $this->render('employeedetails', [
            'employees' => $employees,
        ]);
    }

    /**
     * Deletes employee.
     */
    public function actionDelete($EmployeeId)
    {
        $employee = $this->findEmployee($EmployeeId);
        $employee->delete();
        Yii::$app->session->setFlash('success', 'Employee deleted.');

        return $this->redirect(['employeedetails']);
    }

    /**
     * Displays form to edit an employee.
     *
     */
    public function actionEdit($EmployeeId)
{
    $employee = $this->findEmployee($EmployeeId);

    if ($employee->load(Yii::$app->request->post())) {
        $employee->edited_by = Yii::$app->user->identity->username;
        $employee->edited_on = date('Y-m-d H:i:s');

        if ($employee->save()) {
            Yii::$app->session->setFlash('success', 'Employee updated.');
            return $this->redirect(['employeedetails']);
        }
    }

    return $this->render('edit', [
        'employee' => $employee,
    ]);
}


    /**
     * Find employeetb model based on employeeid.
     */
    protected function findEmployee($EmployeeId)
    {
        $employee = EmployeeTb::findOne($EmployeeId);
        if ($employee === null) {
            throw new NotFoundHttpException('Employees not found with those.');
        }
        return $employee;
    }

    /**
     * Creates new employee.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $employee = new EmployeeTb();

    if ($employee->load(Yii::$app->request->post())) {
        $employee->created_by = Yii::$app->user->identity->username;
        $employee->created_on = date('Y-m-d H:i:s');

        if ($employee->save()) {
            Yii::$app->session->setFlash('success', 'Employee created.');
            return $this->redirect(['employeedetails']);
        }
    }

    return $this->render('create', [
        'employee' => $employee,
    ]);
}

    /**
     * Admin Register
     */
    public function actionRegister()
    {
        $model = new Admin();
    
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
    
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
    
            if ($model->save()) {
                return $this->redirect(['login']);
            }
        }
    
        return $this->render('register', [
            'model' => $model,
        ]);
    }
}
