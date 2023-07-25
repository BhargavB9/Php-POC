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
use yii\web\UploadedFile;

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

        $imageFile = UploadedFile::getInstance($employee, 'imageFile');
        $documentFile = UploadedFile::getInstance($employee, 'documentFile');

        if ($imageFile !== null || $documentFile !== null) {
            $uploadPath = Yii::getAlias('@app/uploads');

            if ($imageFile !== null) {
                $employee->image_path = $this->saveUploadedFile($imageFile, $uploadPath . '/images');
                if ($employee->image_path === null) {
                    Yii::$app->session->setFlash('error', 'Failed to save image file.');
                    return $this->refresh();
                }
            }

            if ($documentFile !== null) {
                $employee->document_path = $this->saveUploadedFile($documentFile, $uploadPath . '/documents');
                if ($employee->document_path === null) {
                    Yii::$app->session->setFlash('error', 'Failed to save document file.');
                    return $this->refresh();
                }
            }
        }

        if ($employee->save()) {
            Yii::$app->session->setFlash('success', 'Employee updated.');
            return $this->redirect(['employeedetails']);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to save employee.');
        }
    }

    return $this->render('edit', [
        'employee' => $employee,
    ]);
}

private function saveUploadedFile($file, $directory)
{
    $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
    $filePath = $directory . '/' . $fileName;

    if ($file->saveAs($filePath)) {
        return $filePath;
    }

    return null;
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
        $employee->imageFile = UploadedFile::getInstance($employee, 'imageFile');
        $employee->documentFile = UploadedFile::getInstance($employee, 'documentFile');

        $employee->created_by = Yii::$app->user->identity->username;
        $employee->created_on = date('Y-m-d H:i:s');

        if ($employee->validate()) {
            $imageName = Yii::$app->security->generateRandomString() . '.' . $employee->imageFile->extension;
            $documentName = Yii::$app->security->generateRandomString() . '.' . $employee->documentFile->extension;

            $uploadPath = Yii::getAlias('@app/uploads');

            $imageDirectory = $uploadPath . '/images';
            $documentDirectory = $uploadPath . '/documents';
            if (!is_dir($imageDirectory)) {
                mkdir($imageDirectory, 0777, true);
            }
            if (!is_dir($documentDirectory)) {
                mkdir($documentDirectory, 0777, true);
            }

            $employee->image_path = $imageDirectory . '/' . $imageName;
            $employee->document_path = $documentDirectory . '/' . $documentName;

            if ($employee->save()) {
                $employee->imageFile->saveAs($employee->image_path);
                $employee->documentFile->saveAs($employee->document_path);

                Yii::$app->session->setFlash('success', 'Employee created.');
                return $this->redirect(['employeedetails']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save employee.');
            }
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
