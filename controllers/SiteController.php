<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use app\models\User;

class SiteController extends Controller
{
    // public $defaultAction = 'about';
    // public $layout = 'story';
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

public function actionUserInfo()
{
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']); 
    }
    $user = Yii::$app->user->identity;

    return $this->render('userinfo', [
        'user' => $user, 
    ]);
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
        return $this->redirect(['site/user-info']);
    }

    $model->password = '';
    return $this->render('login', [
        'model' => $model,
    ]);
}

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->name = $model->name;
            $user->login = $model->login;
            $user->email = $model->email;
            $user->phone = $model->phone;
            $user->address = $model->address;
            $user->setPassword($model->password); 
            $user->generateAuthKey(); 

            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Регистрация успешна.');
                return $this->redirect(['site/login']); 
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка регистрации. Пожалуйста, попробуйте еще раз.');
            }
        }
        return $this->render('register', [
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


}
