<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "empleados".
 *
 * @property int    $id_empleado
 * @property string $nombre
 * @property string $apellido
 * @property string $puesto
 * @property string $telefono
 * @property string $correo_electronico
 * @property float  $salario
 *
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $role
 *
 * @property Mesa[] $mesas
 */
class Empleado extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empleados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Campos siempre requeridos
            [['nombre', 'apellido', 'correo_electronico'], 'required'],
            [['salario'], 'number'],
            [['nombre', 'apellido', 'correo_electronico'], 'string', 'max' => 100],
            [['puesto'], 'string', 'max' => 50],
            [['telefono'], 'string', 'max' => 20],
            [['role'], 'string', 'max' => 20],
            [['correo_electronico'], 'unique'],

            // Solo en escenario "create" se requieren estos campos y su unicidad
            [['username', 'password_hash', 'auth_key'], 'required', 'on' => 'create'],
            [['username'], 'string', 'max' => 50, 'on' => 'create'],
            [['auth_key'], 'string', 'max' => 32, 'on' => 'create'],
            [['username'], 'unique', 'on' => 'create'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // En creación validamos todos los campos relevantes, incluyendo credenciales
        $scenarios['create'] = [
            'nombre',
            'apellido',
            'correo_electronico',
            'username',
            'password_hash',
            'auth_key',
            'role',
            'salario',
            'puesto',
            'telefono',
        ];
        // En actualización solo validamos los campos de perfil
        $scenarios['update'] = [
            'nombre',
            'apellido',
            'correo_electronico',
            'salario',
            'puesto',
            'telefono',
            'role',
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_empleado'        => 'ID',
            'nombre'             => 'Nombre',
            'apellido'           => 'Apellido',
            'puesto'             => 'Puesto',
            'telefono'           => 'Teléfono',
            'correo_electronico' => 'Correo Electrónico',
            'salario'            => 'Salario',
            'username'           => 'Usuario',
            'password_hash'      => 'Password Hash',
            'auth_key'           => 'Auth Key',
            'role'               => 'Rol',
        ];
    }

    /**
     * Gets query for [[Mesas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMesas()
    {
        return $this->hasMany(Mesa::class, ['id_empleado' => 'id_empleado']);
    }

    // ===== IdentityInterface methods =====

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id_empleado;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
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
     * Validates password
     *
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}
