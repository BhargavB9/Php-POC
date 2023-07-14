<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Admin';
    }

    /**
     * Finds an identity by the given ID.
     *
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds an identity by the given username.
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Returns the user ID.
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the auth key.
     *
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validates the auth key.
     *
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates the password.
     *
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    
    /**
     * Sets the password hash for the user.
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
}
