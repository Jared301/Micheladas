<?php
namespace app\models;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['username','password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = Empleado::findByUsername($this->username);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuario o contraseña incorrectos.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                Empleado::findByUsername($this->username),
                $this->rememberMe ? 3600*24*30 : 0
            );
        }
        return false;
    }
}
