<?php

namespace app\modules\api\actions\user;

use app\models\RegisterForm;
use yii\base\Exception;
use yii\rest\Action;
use Yii;

class RegisterAction extends Action
{
    public $params = [];

    /**
     * @throws Exception
     */
    public function run()
    {
        $registerForm = new RegisterForm();

        if ($registerForm->load($this->params, '') && $registerForm->register()) {
            $user = $registerForm->getUser();

            return ['uid' => $user->id];
        }

        Yii::$app->response->statusCode = 422;
        return ['message' => $registerForm->getErrorSummary(true)];
    }
}