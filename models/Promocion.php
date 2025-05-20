<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "promociones".
 *
 * @property int $id_promocion
 * @property string $nombre
 * @property string $descripcion
 * @property float $descuento
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property int $id_producto
 *
 * @property Producto $producto
 */
class Promocion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promociones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'descuento', 'fecha_inicio', 'fecha_fin', 'id_producto'], 'required'],
            [['descripcion'], 'string'],
            [['descuento'], 'number'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['id_producto'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['id_producto'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::class, 'targetAttribute' => ['id_producto' => 'id_producto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_promocion' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
            'descuento' => 'Descuento',
            'fecha_inicio' => 'Fecha de Inicio',
            'fecha_fin' => 'Fecha de Fin',
            'id_producto' => 'Producto',
        ];
    }

    /**
     * Gets query for [[Producto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Producto::class, ['id_producto' => 'id_producto']);
    }
}