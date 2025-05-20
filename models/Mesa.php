<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mesas".
 *
 * @property int $id_mesa
 * @property int $numero_mesa
 * @property int $capacidad
 * @property string $estado
 * @property int $id_empleado
 *
 * @property Empleado $empleado
 */
class Mesa extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mesas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_mesa', 'capacidad', 'id_empleado'], 'required'],
            [['numero_mesa', 'capacidad', 'id_empleado'], 'integer'],
            [['estado'], 'string', 'max' => 20],
            [['id_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['id_empleado' => 'id_empleado']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_mesa' => 'ID Mesa',
            'numero_mesa' => 'Número de Mesa',
            'capacidad' => 'Capacidad',
            'estado' => 'Estado',
            'id_empleado' => 'Empleado Asignado',
        ];
    }

    /**
     * Gets query for [[Empleado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleado::class, ['id_empleado' => 'id_empleado']);
    }
}