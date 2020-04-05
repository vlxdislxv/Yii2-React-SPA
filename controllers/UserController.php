<?php


namespace app\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use Yii;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass
            ],
            'login' => [
                'class' => 'app\actions\user\LoginAction',
                'modelClass' => $this->modelClass,
                'params' => Yii::$app->request->post()
            ],
            'register' => [
                'class' => 'app\actions\user\RegisterAction',
                'modelClass' => $this->modelClass,
                'params' => Yii::$app->request->post()
            ]
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBasicAuth::class,
                HttpBearerAuth::class,
                QueryParamAuth::class,
            ],
            'optional' => ['login', 'register']
        ];
        return $behaviors;
    }
}