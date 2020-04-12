<?php

namespace app\models;

use Firebase\JWT\JWT;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * Class User
 * @package app\models
 * @property $id int
 * @property $username string
 * @property $password string
 * @property $email string
 * @property $authKey string
 */
class User extends ActiveRecord implements IdentityInterface
{

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required']
        ];
    }

    public function generateJWT()
    {
        $key = Yii::$app->request->cookieValidationKey;

        $data = [
            'uip' => Yii::$app->request->userIP,
            'uid' => $this->id,
            'time' => time()
        ];

        return JWT::encode($data, $key);
    }

    public static function isExpiredJWT($token)
    {
        $expire = Yii::$app->params['jwtExpire'];

        return ($token->time + $expire) < time();
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'email'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $key = Yii::$app->request->cookieValidationKey;
        $algorithm = Yii::$app->params['jwtAlgorithm'];

        try {
            $decoded = JWT::decode($token, $key, [$algorithm]);
        } catch (\Exception $ex) {
            return NULL;
        }

        if (static::isExpiredJWT($decoded)) return NULL;

        return static::findOne(['id' => $decoded->uid]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
