<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\base\DynamicModel;
use yii\db\Query;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * Summary of beforeAction
     * @param mixed $action
     * @return bool
     */
    // public function beforeAction($action)
    // {
    //     if (in_array($action->id, ['your-action'])) {
    //         $this->enableCsrfValidation = false;
    //     }
    //     return parent::beforeAction($action);
    // }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
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

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Summary of actionTestAll
     * @return array
     */
    public function actionTestAll()
    {
        try {
            # Json request reader
            $rawPostData = file_get_contents('php://input');
            \Yii::info('Raw POST Data: ' . $rawPostData, __METHOD__);
            $data = json_decode($rawPostData, true);
            $dataArray = $data["data"];

            # Validation
            foreach ($dataArray as $index => $item) {
                $model = DynamicModel::validateData($item, [
                    [['username', 'email', 'password'], 'required'],
                    ['email', 'email'],
                    [
                        'password',
                        'match',
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'message' => 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.'
                    ],
                ]);
                if ($model->hasErrors()) {
                    return validationError($model);
                }
            }

            # Bulk data entry
            // $transaction = Yii::$app->db->beginTransaction();
            // if (!empty($dataArray)) {
            //     $db = Yii::$app->db;
            //     $command = $db->createCommand()->batchInsert('user', ['username', 'email','password'], $dataArray);
            //     $command->execute();
            // }
            // $transaction->commit();

            # Create a Query Builder instance
            $query = new Query();
            $users = $query->select(['*'])
                ->from('user')
                ->where(['status' => 1])
                ->orderBy('username ASC')
                ->all();

            # Second Database connectivity 
            $db2Users = (new Query())
                ->select('*')
                ->from('demo_tables')
                ->all(Yii::$app->db2);

            $responseData = [
                "db1" => $users,
                "db2" => $db2Users
            ];
            return  responseMsg(true, "jason decode!", $responseData);
        } catch (\Exception $e) {
            // $transaction->rollBack();
            return responseMsg(false, $e->getMessage(), []);
        }
    }


    /**
     * Summary of actionCommand
     * @return array
     */
    public function actionCommand()
    {
        try{
            # calling a functionin the command controller 
            $mUser = new User();
            // $mUser->generateRandomUser();
        }
        catch (\Exception $e){
            return responseMsg(false, $e->getMessage(), []);
        }
    }
}
