<?php

namespace app\actions\user;

use app\models\LoginForm;
use yii\rest\Action;
use Yii;

class LoginAction extends Action
{
    public $params = [];

    public function run()
    {
        $loginForm = new LoginForm();

        if ($loginForm->load($this->params, '') && $loginForm->login()) {
            $user = $loginForm->getUser();

            return [
                'token' => $user->generateJWT(),
                'id' => $user->id,
                'username' => $user->username
            ];
        }

        Yii::$app->response->statusCode = 401;
        return ['message' => $loginForm->getErrorSummary(true)];
    }
}